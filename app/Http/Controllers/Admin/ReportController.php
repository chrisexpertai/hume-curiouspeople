<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Reports';

        // Filters
        $titleInput = trim((string) $request->get('jt'));
        $jobTitleTokens = $titleInput !== '' ? preg_split('/\s*\|\s*/', $titleInput, -1, PREG_SPLIT_NO_EMPTY) : ['Manager', 'Director'];
        $onlyActiveUsers = $request->has('users_active') ? (bool) $request->get('users_active') : true;
        $onlyActiveCourses = $request->has('courses_active') ? (bool) $request->get('courses_active') : true;

        // Sorting
        $allowedSort = [
            'user_id' => 'user_id',
            'job_title' => 'job_title',
            'full_name' => 'full_name',
            'total_required' => 'total_required',
            'total_completed' => 'total_completed',
            'completion_pct' => 'completion_pct',
        ];
        $sort = $allowedSort[$request->get('sort') ?? 'job_title'] ?? 'job_title';
        $dir = strtolower($request->get('dir') ?? 'asc');
        $dir = in_array($dir, ['asc', 'desc']) ? $dir : 'asc';

        // Build dynamic SQL with bindings
        $bindings = [];
        $conditions = [];

        if ($onlyActiveUsers) {
            $conditions[] = 'u.active = 1';
            $conditions[] = 'u.active_status = 1';
        }
        if ($onlyActiveCourses) {
            $conditions[] = 'c.status = 1';
        }

        if (count($jobTitleTokens)) {
            $titleConds = [];
            foreach ($jobTitleTokens as $tok) {
                $titleConds[] = 'u.job_title LIKE ?';
                $bindings[] = '%' . $tok . '%';
            }
            $conditions[] = '(' . implode(' OR ', $titleConds) . ')';
        }

        $whereSql = count($conditions) ? ('WHERE ' . implode(' AND ', $conditions)) : '';

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
{$whereSql}
GROUP BY u.id, u.name, u.job_title
ORDER BY {$sort} {$dir}
SQL;

        $rows = DB::select($sql, $bindings);

        // Load most recent previous snapshot month available (before current month)
        $lastSnapshotPctByUser = [];
        $currentMonthStart = date('Y-m-01');
        $lastSnapshotMonth = null;
        if (Schema::hasTable('manager_completion_snapshots')) {
            $lastSnapshotMonth = DB::table('manager_completion_snapshots')
                ->where('snapshot_month', '<', $currentMonthStart)
                ->max('snapshot_month');

            if ($lastSnapshotMonth) {
                $snapRows = DB::table('manager_completion_snapshots')
                    ->where('snapshot_month', $lastSnapshotMonth)
                    ->get();
                foreach ($snapRows as $s) {
                    $lastSnapshotPctByUser[(int) $s->user_id] = (float) $s->completion_pct;
                }
            }
        }

        return view('admin.reports.index', [
            'title' => $title,
            'rows' => $rows,
            'lastSnapshotPctByUser' => $lastSnapshotPctByUser,
            'lastSnapshotMonth' => $lastSnapshotMonth,
            // persist filter state
            'filters' => [
                'jt' => $titleInput,
                'users_active' => $onlyActiveUsers,
                'courses_active' => $onlyActiveCourses,
                'sort' => $sort,
                'dir' => $dir,
            ],
        ]);
    }

    public function snapshot(Request $request)
    {
        // Ensure storage table exists
        if (!Schema::hasTable('manager_completion_snapshots')) {
            return back()->with('error', 'Snapshot table manager_completion_snapshots does not exist. Please create it using the provided SQL.');
        }

        // Reuse the main query
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
        $snapshotMonth = date('Y-m-01');

        DB::transaction(function () use ($rows, $snapshotMonth) {
            // Replace existing snapshot for this month
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

            // Insert in chunks for safety
            foreach (array_chunk($insert, 500) as $chunk) {
                DB::table('manager_completion_snapshots')->insert($chunk);
            }
        });

        return back()->with('success', 'Snapshot saved for ' . date('F Y', strtotime($snapshotMonth)) . '.');
    }

    public function userCourses(Request $request)
    {
        $title = 'Reports - User Courses';

        $userId = $request->get('user_id');
        $bindings = [];

        $where = "WHERE c.status = 1";
        if (!empty($userId)) {
            $where .= " AND u.id = ?";
            $bindings[] = (int) $userId;
        }

        $sql = <<<SQL
SELECT
    u.id AS user_id,
    u.name AS user_name,
    c.id AS course_id,
    c.title AS course_name,
    c.status AS `status`,
    CASE
        WHEN e.id IS NOT NULL THEN 'Enrolled' 
        ELSE 'Not Enrolled'
    END AS enrolment_status,
    e.enrolled_at,
    CASE
        WHEN a.id IS NOT NULL THEN 'Completed'
        ELSE 'Not Completed'
    END AS completion_status,
    a.completed_at,
    CASE
        WHEN a.id IS NOT NULL THEN 1
        ELSE 0
    END AS pass_flag
FROM users u
CROSS JOIN courses c
LEFT JOIN (
    SELECT
        id,
        user_id,
        course_id,
        course_price,
        enrolled_at
    FROM enrolls
    WHERE STATUS = 'success'
) e
    ON e.user_id = u.id
   AND e.course_id = c.id 
LEFT JOIN (
    SELECT
        MIN(id) AS id,
        user_id,
        course_id,
        MIN(ended_at) AS completed_at
    FROM attempts
    WHERE STATUS = 'finished'
      AND passed = 1
    GROUP BY user_id, course_id
) a
    ON a.user_id = u.id
   AND a.course_id = c.id
{$where}
ORDER BY u.id, c.id
SQL;

        $rows = DB::select($sql, $bindings);

        // Users for filter dropdown (active by default, sorted by name)
        $users = DB::table('users')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('admin.reports.user_courses', [
            'title' => $title,
            'rows' => $rows,
            'users' => $users,
            'selectedUserId' => $userId,
        ]);
    }
}
