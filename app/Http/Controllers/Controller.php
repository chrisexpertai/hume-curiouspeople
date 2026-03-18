<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        if ($this->isInstallationCompleted()) {
            view()->composer('*', function ($view) {
                $authUser = Auth::user();
                $view->with(['auth_user' => $authUser]);
            });
        }
    }

    private function isInstallationCompleted()
{
    try {
        // Attempt to establish a database connection
        DB::connection()->getPdo();

        // If the connection is successful, check if the users table exists
        if (Schema::hasTable('users')) {
            // If the users table exists, consider installation completed
            return true;
        } else {
            // If the users table does not exist, consider installation not completed
            return false;
        }
    } catch (\Exception $e) {
        // If an exception occurs (indicating a database connection error),
        // consider installation not completed
        return false;
    }
}

}
