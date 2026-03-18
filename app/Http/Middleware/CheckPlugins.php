<?php

namespace App\Http\Middleware;

use Closure;

class CheckPlugins
{
    public function handle($request, Closure $next, $plugin)
    {
        $active_plugins = json_decode(get_option('active_plugins'), true); // Decode JSON string to array

        if (!is_array($active_plugins) || !in_array($plugin, $active_plugins)) {
            abort(404); // Return 404 if the specified plugin is not active
        }

        return $next($request);
    }
}
