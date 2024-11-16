<?php

namespace App\Events;

use App\Models\Group;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserTyping implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $conversation;
    public $isTyping;

    /**
     * Create a new event instance.
     */
    public function __construct($conversation, $isTyping)
    {
        $this->conversation = $conversation;
        $this->isTyping = $isTyping;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        if($this->conversation instanceof Group) {
            return [
                new PresenceChannel('groups.' . $this->conversation->id)
            ];
        } else {
            return [
                new PrivateChannel('chats.' . $this->conversation->id)
            ];
        }
    }

    public function broadcastWith()
    {
        return [
            'isTyping' => $this->isTyping
        ];
    }
}
