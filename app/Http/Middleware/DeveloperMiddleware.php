<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class DeveloperMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'developer') {
            return $next($request);
        }

        return redirect('/login')->with('error', 'Akses ditolak!');
    }
}
