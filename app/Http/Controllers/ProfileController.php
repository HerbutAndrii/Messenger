<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request, User $user) 
    {
        if($contact = $request->user()->contacts()->where('user_id', $user->id)->first()) {
            return redirect(route('contacts.show', $contact));
        }

        return view('users.show', compact('user'));
    }
}
