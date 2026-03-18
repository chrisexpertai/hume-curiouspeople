<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class CheckInstallation
{
  public function handle($request, Closure $next)
  {
    try {
      DB::connection()->getPdo();
    } catch (\Exception $e) {
      if ($request->path() !== 'install') {
        return redirect()->route('install'); // Use the route helper
      }
    }

    return $next($request);
  }
}
