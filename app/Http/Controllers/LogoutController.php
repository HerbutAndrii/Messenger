<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout() 
    {
        auth()->logout();

        session()->invalidate();

        session()->regenerateToken();

        return redirect(route('login.show'));
    }
}
