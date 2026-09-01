<?php

namespace App\Http\Resources\Messaging;

use App\Http\Resources\Identity\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ConversationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $unreadCount = 0;
        if (Auth::check()) {
            $member = $this->participants->firstWhere('id', Auth::id());
            if ($member && $member->pivot) {
                $lastSeenId = $member->pivot->last_seen_message_id ?? 0;
                $unreadCount = $this->messages()
                    ->where('id', '>', $lastSeenId)
                    ->where('sender_id', '!=', Auth::id())
                    ->count();
            }
        }

        return [
            'id' => $this->id,
            'type' => $this->conversation_type,
            'name' => $this->name,
            'avatar' => $this->avatar,
            'participants' => UserResource::collection($this->whenLoaded('participants')),
            'unread_count' => $unreadCount,
            'last_message' => $this->lastMessage ? [
                'content' => $this->lastMessage->content,
                'sent_at' => $this->lastMessage->sent_at,
            ] : null,
            'updated_at' => $this->updated_at,
        ];
    }
}
