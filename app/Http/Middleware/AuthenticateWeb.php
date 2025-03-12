<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticateWeb
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle($request, Closure $next)
    {

       // Redirect authenticated users away from the login page
        if (Auth::guard('web')->check()) {
            if ($request->routeIs('login')) {
                return redirect()->route('dashboard'); // Redirect to a protected route
            }
        } else {
            // Redirect unauthenticated users to the login page
            if (!$request->routeIs('login') && !$request->routeIs('post.login.web')) {
                return redirect()->route('login');
            }
        }

        return $next($request);

    }
}