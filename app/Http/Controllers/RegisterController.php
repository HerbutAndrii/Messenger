<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    public function show() 
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]); 

        if($request->hasFile('avatar')) {
            $fileName = $request->file('avatar')->hashName();
            $request->file('avatar')->storeAs('public/avatars', $fileName);
            $user->avatar = $fileName;
        } else {
            Storage::copy('/public/defaults/default-avatar.jpg', '/public/avatars/default-avatar.jpg');
        }

        $user->save();

        auth()->login($user);

        session()->regenerate();

        return redirect(route('chats.index'));
    }
}
