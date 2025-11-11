<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class DesignerMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'designer') {
            return $next($request);
        }

        return redirect('/login')->with('error', 'Akses ditolak!');
    }
}
