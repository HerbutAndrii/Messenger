<?php

use App\Models\Chat;
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

Broadcast::channel('Chat.{id}', function ($user, $id) {
    $chat = Chat::find($id);
    
    return $chat && ($chat->user_one_id === $user->id || $chat->user_two_id === $user->id);
});
