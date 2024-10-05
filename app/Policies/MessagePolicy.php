<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    public function create(User $user, Chat $chat) 
    {
        return $user->chats()->where('chats.id', $chat->id)->exists();
    }

    public function update(User $user, Message $message)
    {
        return $user->messages()->where('id', $message->id)->exists();
    }

    public function delete(User $user, Message $message) 
    {
        return $user->messages()->where('id', $message->id)->exists();
    }
}
