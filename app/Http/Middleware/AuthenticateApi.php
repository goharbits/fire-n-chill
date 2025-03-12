<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticateApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

  public function handle(Request $request, Closure $next): Response
    {

        // Check if the request has a bearer token
        if ($request->bearerToken() && Auth::guard('api')->check() && Auth::guard('api')->user()->mainbody_token) {
            return $next($request);
        }

        // If the user is not authenticated, return an unauthorized response
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }
}