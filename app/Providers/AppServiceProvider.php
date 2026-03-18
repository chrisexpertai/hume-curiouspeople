<?php

namespace App\Providers;
use App\Models\Option;

use App\Models\Setting;
use App\Models\CustomCss;
use App\Models\TeacherApplication;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Models\Notification;
use App\Models\SubscriptionPlan;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        if (file_exists(base_path('.env'))) {
            try {
                DB::connection()->getPdo();


                if ($this->necessaryTablesExist()) {


                // Retrieve ad code from Google AdSense
                $bannerAdCode = get_option('ads_banner');
                $sidebarAdCode = '<!-- Google AdSense Sidebar Ad Code -->';

                // Share ad code with all views
                view()->share('bannerAdCode', $bannerAdCode);
                view()->share('sidebarAdCode', $sidebarAdCode);


                // Share the unread notifications count and unread notifications with all views
                View::composer('*', function ($view) {
                    // Check if the current user is authenticated
                    if (auth()->check()) {
                        // Get the currently authenticated user
                        $user = auth()->user();

                        // Check if the user is an admin
                        if ($user->isAdmin()) {
                            // Get the count of unread notifications for the admin user
                            $unreadNotificationsCount = $user->notifications()->where('read', 0)->count();

                            // Get all unread notifications for the admin user
                            $unreadAdminNotifications = $user->notifications()->where('read', 0)->get();

                            // Share the data with all views
                            $view->with([
                                'unreadNotificationsCount' => $unreadNotificationsCount,
                                'unreadAdminNotifications' => $unreadAdminNotifications,
                            ]);
                        }
                    }
                });

                // Fetch only unread admin notifications
                $adminNotifications = Notification::whereHas('user', function ($query) {
                    $query->where('user_type', 'admin');
                })->where('read', 0)->get();

                // Share admin notifications with all views
                View::share('adminNotifications', $adminNotifications);


                View::composer('*', function ($view) {
                    $menus = \App\Models\Menu::all();
                    $view->with('menus', $menus);
                });


                View::composer('*', function ($view) {
                    $pendingApplicationsCount = TeacherApplication::where('status', 'pending')->count();
                    $view->with('pendingApplicationsCount', $pendingApplicationsCount);
                });

                

                Schema::defaultStringLength(191);

                if (file_exists(base_path('.env'))) {
                    try {
                        DB::connection()->getPdo();

                        /**
                         * Get option and set it to config
                         */
                        $options = Option::all()->pluck('option_value', 'option_key')->toArray();
                        $configs = [];
                        $configs['options'] = $options;

                        /**
                         * Get option in some specific way
                         */
                        $configs['options']['allowed_file_types_arr'] = array_filter(explode(',', array_get($options, 'allowed_file_types')));
                        /**
                         * Load language file from theme
                         */
                        $theme_slug = array_get($options, 'current_theme');
                        $local = app()->getLocale();


                        $customCss = CustomCss::latest()->first();
                        view()->share('customCss', $customCss);

                        $configs['app.timezone'] = array_get($options, 'default_timezone');
                        $configs['app.url'] = array_get($options, 'site_url');
                        $configs['app.name'] = array_get($options, 'site_title');
                        // Retrieve settings
                        $settings = Setting::first();

                        // Share settings with all views
                        View::share('settings', $settings);

                        $configs = apply_filters('app_configs', $configs);
                        config($configs);

                        /**
                         * Set dynamic configuration for third party services
                         */

                        /**
                         * Set dynamic configuration for third party services
                         */
                        $amazonS3Config = [
                            'filesystems.disks.s3' =>
                                [
                                    'driver' => 's3',
                                    'key' => get_option('amazon_key'),
                                    'secret' => get_option('amazon_secret'),
                                    'region' => get_option('amazon_region'),
                                    'bucket' => get_option('bucket'),
                                ]
                        ];

                        $socialConfig['services'] = [
                            'facebook' => [
                                'client_id' => get_option('social_login.facebook.app_id'),
                                'client_secret' => get_option('social_login.facebook.app_secret'),
                                'redirect' => url('login/facebook/callback'),
                            ],
                            'google' => [
                                'client_id' => get_option('social_login.google.client_id'),
                                'client_secret' => get_option('social_login.google.client_secret'),
                                'redirect' => url('login/google/callback'),
                            ],
                            'twitter' => [
                                'client_id' => get_option('social_login.twitter.consumer_key'),
                                'client_secret' => get_option('social_login.twitter.consumer_secret'),
                                'redirect' => url('login/twitter/callback'),
                            ],
                            'linkedin' => [
                                'client_id' => get_option('social_login.linkedin.client_id'),
                                'client_secret' => get_option('social_login.linkedin.client_secret'),
                                'redirect' => url('login/linkedin/callback'),
                            ],
                        ];
                        config($socialConfig);
                        config($amazonS3Config);

                        /**
                         * Email from name
                         */

                        $emailConfig = [
                            'mail.from' =>
                                [
                                    'address' => get_option('email_address'),
                                    'name' => get_option('site_name'),
                                ]
                        ];
                        config($emailConfig);

                        date_default_timezone_set(array_get($options, 'default_timezone'));

                        require get_theme()->path . 'options.php';

                    } catch (\Exception $e) {
                        //
                    }
                } else {
                    if (!strpos(request()->getPathInfo(), 'final')) {
                        die("<script>location.href='" . url('final') . "'</script>");
                    }
                }
            }
            } catch (\Exception $e) {
                //
            }
        }
    }
    private function necessaryTablesExist()
    {
        // Define necessary tables
        $necessaryTables = [
            'notifications',
            'users',
            // Add more tables if needed
        ];

        // Check if all necessary tables exist
        foreach ($necessaryTables as $table) {
            if (!Schema::hasTable($table)) {
                return false;
            }
        }

        return true;
    }
}
