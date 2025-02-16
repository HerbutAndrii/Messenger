<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
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

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $user->update(['name' => $request->name]);

        return response()->json(['user' => new UserResource($user)]);
    }
}
