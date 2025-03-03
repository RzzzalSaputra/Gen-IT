<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AuthenticatedSessionController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Authenticate user and create token",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="itsuki@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="Password123!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials"
     *     )
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'), false)) {
            return response()->json([
                'message' => 'Invalid login credentials'
            ], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Generate new auth token and store in remember_token
        $token = Str::random(60);
        $user->remember_token = $token;
        $user->save();

        // Create HTTP-only cookie
        $cookie = cookie(
            'auth_token',
            $token,
            720, // 12 hours
            '/',
            null,
            true,  // secure
            true   // httpOnly
        );

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token 
        ])->withCookie($cookie);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout user and revoke token",
     *     tags={"Authentication"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function logout(Request $request)
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            
            if ($user) {
                $user->remember_token = null;
                $user->save();
            }

            Auth::logout();
            $cookie = Cookie::forget('auth_token');

            return response()->json([
                'message' => 'Logged out successfully'
            ])->withCookie($cookie);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to logout'
            ], 500);
        }
    }
}