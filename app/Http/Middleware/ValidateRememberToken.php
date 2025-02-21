<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class ValidateRememberToken
{
    public function handle(Request $request, Closure $next)
    {
        // Try to get the token from cookie or authorization header
        $token = $request->cookie('auth_token');
        
        // If not in cookie, check bearer token
        if (!$token) {
            $token = $request->bearerToken();
        }
    
        if (!$token) {
            return response()->json(['message' => 'Unauthorized - No token provided'], 401);
        }
    
        // Debug - log the token being checked
        Log::debug('Checking token: ' . substr($token, 0, 10) . '...');
        
        $user = User::where('remember_token', $token)->first();
    
        if (!$user) {
            return response()->json(['message' => 'Invalid or expired token'], 401);
        }
    
        Auth::login($user);
        return $next($request);
    }
}