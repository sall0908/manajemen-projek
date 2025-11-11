<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Jika sudah login, arahkan ke dashboard sesuai role
                $role = Auth::user()->role ?? 'user';
                switch ($role) {
                    case 'admin':
                        return redirect()->route('admin.dashboard');
                    case 'teamlead':
                    case 'teamleader':
                        return redirect()->route('teamleader.dashboard');
                    case 'designer':
                        return redirect()->route('designer.dashboard');
                    case 'developer':
                        return redirect()->route('developer.dashboard');
                    default:
                        return redirect()->route('users.dashboard');
                }
            }
        }

        return $next($request);
    }
}
