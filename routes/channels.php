<?php

use App\Models\Chat;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('chats.{chat}', function (User $user, Chat $chat) {
    return $chat && ($chat->user_one_id === $user->id || $chat->user_two_id === $user->id);
});

Broadcast::channel('groups.{group}', function (User $user, Group $group) {
    if($group->users()->where('users.id', $user->id)->exists()) {
        return ['id' => $user->id, 'name' => $user->name];
    }
});
