<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChatResource;
use App\Http\Resources\ContactResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index() 
    {
        return view('users.index');
    }

    public function users(Request $request)
    {
        $users = User::where('name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%')
                ->get();
        
        return response()->json(UserResource::collection($users));
    }

    public function members(Request $request, Group $group)
    {
        $members = $group->users()->where(function ($query) use ($request) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%');
        })->get();

        return response()->json(UserResource::collection($members));
    }

    public function chats(Request $request)
    {
        $chats = $request->user()->chats()->get();

        $chats = $chats->filter(function ($chat) use ($request) {
            return stripos($chat->userName(), $request->search) !== false; 
        });

        return response()->json(ChatResource::collection($chats));
    }

    public function contacts(Request $request)
    {
        $contacts = $request->user()->contacts()->get();

        $contacts = $contacts->filter(function ($contact) use ($request) {
            return stripos($contact->name, $request->search) !== false || stripos($contact->user->email, $request->search) !== false;
        });

        return response()->json(ContactResource::collection($contacts));
    }

    public function groups(Request $request)
    {
        $groups = $request->user()->groups()->where(function ($query) use ($request) {
            $query->where('name', 'like', '%'.$request->search.'%');
        })->get();

        return response()->json(GroupResource::collection($groups));
    }
}
