<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Option;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $expectedRole)
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthorized. User not authenticated.'
            ], 401);
        }

        $user = Auth::user();
        
        // Langsung bandingkan string role
        if ($user->role !== $expectedRole) {
            return response()->json([
                'message' => "Unauthorized. Required role: {$expectedRole}."
            ], 403);
        }

        return $next($request);
    }
}