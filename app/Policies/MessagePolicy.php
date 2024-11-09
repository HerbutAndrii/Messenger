<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\Group;
use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    public function create(User $user, $conversation) 
    {
        if($conversation instanceof Chat) {
            return $user->chats()->where('chats.id', $conversation->id)->exists();
        } else {
            return $user->groups()->where('groups.id', $conversation->id)->exists();
        }
    }

    public function update(User $user, Message $message)
    {
        return $user->messages()->where('id', $message->id)->exists();
    }

    public function delete(User $user, Message $message) 
    {
        if($message->messageable instanceof Group) {
            return $message->messageable->user_id === $user->id || $user->messages()->where('id', $message->id)->exists();
        }
        
        return $user->messages()->where('id', $message->id)->exists();
    }
}
