<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the request has the 'guest' parameter and it equals 'true'
        if ($request->input('guest') !== 'true') {
            // If not, block access and return a 403 forbidden response
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized access'], 403);
            }else{
                return redirect()->route('home');
            }
        }

        // If 'guest=true' is present, allow the request to proceed
        return $next($request);
    }
}