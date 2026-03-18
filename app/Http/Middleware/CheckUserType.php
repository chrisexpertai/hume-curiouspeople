<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserType
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Check if the user is inactive
            if (Auth::user()->user_type == 'inactive') {
                // Restrict access to certain routes
                return redirect()->route('reactivate');
            }
        }

        return $next($request);
    }
}
