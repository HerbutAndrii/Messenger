<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show() 
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request) 
    {
        if(Auth::attempt($request->validated())) {
            $request->session()->regenerate();

            return redirect()->intended(route('chats.index'));
        }

        return back()->withErrors([
            'login' => 'Passwords or email addresses do not match'
        ])->withInput();
    }
}
