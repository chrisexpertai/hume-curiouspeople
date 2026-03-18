<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Helpers\LanguageHelper;

class Language
{
    public function handle($request, Closure $next)
    {
        // Get the current language based on session or system-wide option
        $currentLanguage = get_current_language();

        // Check if the system-wide language option has changed
        $systemLanguage = get_option('language');
        $previousSystemLanguage = Session::get('previous_system_language');

        // If the system-wide language option has changed, clear the session language
        if ($systemLanguage !== $previousSystemLanguage) {
            Session::forget('applocale');
            Session::put('previous_system_language', $systemLanguage);

            // Update the current language to the new system-wide language
            $currentLanguage = $systemLanguage;
        }

        // Set the locale
        App::setLocale($currentLanguage);

        return $next($request);
    }
}
