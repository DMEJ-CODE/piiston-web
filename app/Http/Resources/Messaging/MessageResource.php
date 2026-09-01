<?php

namespace App\Http\Resources\Messaging;

use App\Http\Resources\Identity\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sender_id' => $this->sender_id,
            'sender' => new UserResource($this->whenLoaded('sender')),
            'type' => $this->message_type,
            'content' => $this->content,
            'sent_at' => $this->sent_at,
            'status' => $this->status,
            'reactions' => $this->reactions,
            'media' => $this->media,
        ];
    }
}
