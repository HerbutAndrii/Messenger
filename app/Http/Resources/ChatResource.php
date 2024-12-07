<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_one_id' => $this->user_one_id,
            'user_two_id' => $this->user_two_id,
            'user_avatar' => $this->userAvatar(),
            'user_name' => $this->userName(),
            'latest_message' => $this->latestMessage,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
