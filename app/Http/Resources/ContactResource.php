<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
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
            'name' => $this->name,
            'user_id' => $this->user_id,
            'owner_id' => $this->owner_id,
            'avatar' => $this->user->avatar,
            'email' => $this->user->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
