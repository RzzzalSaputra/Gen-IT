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
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        // First check if the email exists
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak terdaftar dalam sistem kami.',
            ])->onlyInput('email');
        }
        
        // Email exists, now try authentication
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()->withErrors([
                'password' => 'Password yang Anda masukkan salah.',
            ])->onlyInput('email');
        }
        
        // Authentication successful
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
        
        if ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }
        
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