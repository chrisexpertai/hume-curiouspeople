<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TakeManagerCompletionSnapshot extends Command
{
    protected $signature = 'reports:take-snapshot {--month=}';
    protected $description = 'Create a monthly manager/director completion snapshot';

    public function handle()
    {
        if (!Schema::hasTable('manager_completion_snapshots')) {
            $this->error('Table manager_completion_snapshots does not exist. Create it first.');
            return 1;
        }

        $snapshotMonth = $this->option('month') ?: date('Y-m-01');

        $sql = <<<SQL
SELECT 
    u.id AS user_id,
    u.name AS full_name,
    u.job_title,
    COUNT(c.id) AS total_required,
    SUM(
        CASE 
          WHEN CAST(JSON_UNQUOTE(JSON_EXTRACT(u.options, CONCAT('$.completed_courses."', c.id, '".percent'))) AS DECIMAL(10,2)) = 100 
          THEN 1 ELSE 0 
        END
    ) AS total_completed,
    ROUND(
        (SUM(
            CASE 
              WHEN CAST(JSON_UNQUOTE(JSON_EXTRACT(u.options, CONCAT('$.completed_courses."', c.id, '".percent'))) AS DECIMAL(10,2)) = 100 
              THEN 1 ELSE 0 
            END
        ) / COUNT(c.id)) * 100, 1
    ) AS completion_pct
FROM users u
CROSS JOIN courses c
WHERE u.active = 1
  AND u.active_status = 1  
  AND c.status = 1
  AND (u.job_title LIKE '%Manager%' OR u.job_title LIKE '%Director%')
GROUP BY u.id, u.name, u.job_title
ORDER BY u.job_title, u.name
SQL;

        $rows = collect(DB::select($sql));

        DB::transaction(function () use ($rows, $snapshotMonth) {
            DB::table('manager_completion_snapshots')->where('snapshot_month', $snapshotMonth)->delete();

            $insert = $rows->map(function ($r) use ($snapshotMonth) {
                return [
                    'snapshot_month' => $snapshotMonth,
                    'user_id' => $r->user_id,
                    'full_name' => $r->full_name,
                    'job_title' => $r->job_title,
                    'total_required' => (int) $r->total_required,
                    'total_completed' => (int) $r->total_completed,
                    'completion_pct' => (float) $r->completion_pct,
                    'created_at' => now(),
                ];
            })->all();

            foreach (array_chunk($insert, 500) as $chunk) {
                DB::table('manager_completion_snapshots')->insert($chunk);
            }
        });

        $this->info('Snapshot saved for ' . date('F Y', strtotime($snapshotMonth)) . '.');
        return 0;
    }
}

