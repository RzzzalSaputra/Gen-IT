<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Http;

class RegisteredUserController extends Controller
{
    public function store(Request $request)
    {
        $response = Http::post(config('app.url') . '/api/register', [
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birthdate' => $request->birthdate,
        ]);

        if ($response->status() === 422) {
            return back()->withErrors($response->json()['errors'])->withInput();
        }

        return redirect()->route('login')->with('status', 'Registration successful! Please log in.');
    }

    public function create()
    {
        return view('auth.register');
    }
}