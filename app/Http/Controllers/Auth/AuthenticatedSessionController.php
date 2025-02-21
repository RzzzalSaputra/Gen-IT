<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        Auth::attempt($request->only('email', 'password'), false);
        $request->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Generate new auth token and store in remember_token
        $token = Str::random(60);
        $user->remember_token = $token;
        $user->save();

        // Create HTTP-only cookie
        Cookie::queue(
            'auth_token',
            $token,
            720,
            '/',
            null,
            true,
            true
        );
        

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            $user->remember_token = null;
            $user->save();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Cookie::queue(Cookie::forget('auth_token'));

        return redirect('/');
    }
}