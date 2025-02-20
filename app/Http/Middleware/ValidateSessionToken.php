<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class ValidateSessionToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $sessionData = Cache::get('session_token_' . $token);

        if (!$sessionData) {
            return response()->json(['message' => 'Invalid or expired token'], 401);
        }

        $user = User::find($sessionData['user_id']);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 401);
        }

        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}