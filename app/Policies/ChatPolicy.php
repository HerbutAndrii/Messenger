<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\User;

class ChatPolicy
{
    public function view(User $user, Chat $chat) 
    {
        return $user->chats()->where('chats.id', $chat->id)->exists();
    }

    public function delete(User $user, Chat $chat)
    {
        return $user->chats()->where('chats.id', $chat->id)->exists();
    }
}
