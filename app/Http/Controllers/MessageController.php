<?php

namespace App\Http\Controllers;

use App\Events\MessageDeleted;
use App\Events\MessageEdited;
use App\Events\MessageSent;
use App\Http\Requests\Messages\StoreRequest;
use App\Http\Requests\Messages\UpdateRequest;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\Group;
use App\Models\Message;

class MessageController extends Controller
{
    public function chatMessageStore(StoreRequest $request, Chat $chat)
    {
        $this->authorize('create', [Message::class, $chat]);

        $message = $chat->messages()->create([
            'content' => $request->content,
            'user_id' => $request->user()->id
        ]);

        if($chat->users()->count() < 2) {
            $chat->users()->attach($chat->userOne->id === auth()->user()->id ? $chat->userTwo->id : $chat->userOne->id);
        }
            
        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['message' => new MessageResource($message)]);
    }

    public function groupMessageStore(StoreRequest $request, Group $group)
    {
        $this->authorize('create', [Message::class, $group]);

        $message = $group->messages()->create([
            'content' => $request->content,
            'user_id' => $request->user()->id
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['message' => new MessageResource($message)]);
    }

    public function update(UpdateRequest $request, Message $message)
    {
        $this->authorize('update', $message);

        $message->update(['content' => $request->content]);

        broadcast(new MessageEdited($message))->toOthers();

        return response()->json(['message' => new MessageResource($message)]);
    }

    public function destroy(Message $message) 
    {
        $this->authorize('delete', $message);

        $message->delete();

        broadcast(new MessageDeleted($message->id, $message->messageable_id))->toOthers();

        return response()->json(['success' => true]);
    }
}
