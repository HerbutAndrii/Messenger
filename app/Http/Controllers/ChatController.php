<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request) 
    {
        $chats = ChatResource::collection($request->user()->chats()->get());

        return view('chats.index', compact('chats'));
    }

    public function show(Chat $chat) 
    {
        $this->authorize('view', $chat);
        
        $chat = new ChatResource($chat);
        $messages = MessageResource::collection($chat->messages()->get());

        return view('chats.show', compact('chat', 'messages'));
    }

    public function create() 
    {
        return view('chats.create');
    }

    public function store(User $user) 
    {
        $chat = Chat::firstOrCreate([
                'user_one_id' => auth()->user()->id,
                'user_two_id' => $user->id
            ]);

        $chat->users()->attach(auth()->user()->id);

        return redirect(route('chats.show', new ChatResource($chat)));
    }

    public function destroy(Request $request, Chat $chat)
    {
        $this->authorize('delete', $chat);

        $chat->users()->detach($request->user());
        
        if($chat->users()->count() == 0) {
            $chat->delete();
        }

        if($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect(route('chats.index'));
    }
}
