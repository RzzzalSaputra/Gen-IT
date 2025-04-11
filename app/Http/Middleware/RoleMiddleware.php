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
            // API requests get JSON response, web requests get redirected
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized. User not authenticated.'
                ], 401);
            }
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check if user has the expected role
        if ($user->role !== $expectedRole) {
            // API requests get JSON response, web requests get redirected
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Unauthorized. Required role: {$expectedRole}."
                ], 403);
            }
            
            // For web requests, redirect to dashboard with error message
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access that page.');
        }

        return $next($request);
    }
}