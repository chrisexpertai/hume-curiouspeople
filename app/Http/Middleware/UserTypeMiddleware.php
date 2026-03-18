<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTypeMiddleware
{
    public function handle(Request $request, Closure $next, $userType)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Check if the user's user_type matches the allowed user type
            if (Auth::user()->user_type === $userType) {
                return $next($request);
            }
        }

        // Redirect or throw an unauthorized exception
        abort(403, 'Unauthorized');
    }
}
