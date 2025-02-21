<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ValidateRememberToken
{
    public function handle(Request $request, Closure $next)
    {
        // Only check for auth_token (either in cookie or bearer)
        $token = $request->cookie('auth_token') ?? $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Find user by token stored in remember_token
        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid or expired token'], 401);
        }

        Auth::login($user, false); // false prevents Laravel from creating its own remember token

        return $next($request);
    }
}