<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisteredUserController extends Controller
{
    public function store(Request $request)
    {
        // Direct method call - no HTTP request needed
        $apiController = new \App\Http\Controllers\Api\RegisteredUserController();
        $response = $apiController->store($request);
        
        // Process response
        if ($response->getStatusCode() === 422) {
            $responseData = json_decode($response->getContent(), true);
            return back()->withErrors($responseData['errors'])->withInput();
        }
        
        return redirect()->route('login')->with('status', 'Registration successful! Please log in.');
    }

    public function create()
    {
        return view('auth.register');
    }
}