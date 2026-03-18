<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteInactiveAccounts extends Command
{
    protected $signature = 'accounts:delete_inactive';

    protected $description = 'Delete inactive user accounts older than 30 days';

    public function handle()
    {
        $thresholdDate = Carbon::now()->subDays(30);
        $inactiveUsers = User::where('active', false)
            ->where('updated_at', '<', $thresholdDate)
            ->get();

        foreach ($inactiveUsers as $user) {
            $user->delete();
        }

        $this->info(count($inactiveUsers) . ' inactive user accounts deleted.');
    }
}
