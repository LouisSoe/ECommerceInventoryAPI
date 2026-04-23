<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('api')->check() && auth('api')->user()->role === 'admin') {
            return $next($request);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized. Admin access required.'
        ], 403);
    }
}
