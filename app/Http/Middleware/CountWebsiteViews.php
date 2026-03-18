<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Schema; // Import the Schema facade
use Illuminate\Support\Facades\Auth;
use App\Models\WebsiteView;
use Carbon\Carbon;

class CountWebsiteViews
{
    public function handle($request, Closure $next)
    {
        if ($this->isInstallationCompleted() && Schema::hasTable('website_views')) {
            // Get the current date
            $currentDate = \date('Y-m-d');
            $user_id = Auth::user()->id ?? null;

            if ($user_id):
                $sql = "SELECT * FROM website_views w 
                        WHERE DATE_FORMAT(w.created_at, '%Y-%m-%d') = '{$currentDate}' 
                        AND DATE_FORMAT(w.updated_at, '%Y-%m-%d') = '{$currentDate}'
                        AND ip_address = '{$request->ip()}'
                        AND user_agent = '{$request->header('User-Agent')}'
                        AND user_id = {$user_id};";
                $existingView = \DB::select($sql);

                // If no record exists, create a new one
                if (!$existingView) {
                    \DB::table('website_views')->insert([
                        'created_at' => \date('Y-m-d H:i:s'),
                        'updated_at' => \date('Y-m-d H:i:s'),
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->header('User-Agent'),
                        'user_id' => Auth::user()->id ?? $user_id
                    ]);
                }
            endif;
        }

        return $next($request);
    }

    private function isInstallationCompleted()
    {
        try {
            // Attempt to establish a database connection
            \DB::connection()->getPdo();

            // If the connection is successful, consider installation completed
            return true;
        } catch (\Exception $e) {
            // If an exception occurs (indicating a database connection error),
            // consider installation not completed
            return false;
        }
    }
}
