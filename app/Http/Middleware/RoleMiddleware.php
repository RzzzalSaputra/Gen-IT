<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $userRole = $user->roleOption->value ?? null; // Ambil role langsung dari relasi

        if ($userRole !== $expectedRole) {
            return response()->json([
                'message' => "Unauthorized. Required role: {$expectedRole}."
            ], 403);
        }

        return $next($request);
    }
}