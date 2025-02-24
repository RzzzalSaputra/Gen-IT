<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOrUserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if (!in_array(Auth::user()->role, ['admin', 'user'])) {
            return response()->json(['message' => 'Unauthorized. Access restricted.'], 403);
        }

        return $next($request);
    }
}
