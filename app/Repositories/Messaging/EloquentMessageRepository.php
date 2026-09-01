<?php

namespace App\Repositories\Messaging;

use App\Models\Messaging\Message;
use App\Models\Messaging\MessageStatus;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentMessageRepository implements MessageRepositoryInterface
{
    public function findById(int $id): ?Message
    {
        return Message::with(['sender', 'media', 'reactions'])->find($id);
    }

    public function getConversationMessages(int $conversationId, int $perPage = 30): LengthAwarePaginator
    {
        return Message::where('conversation_id', $conversationId)
            ->with(['sender', 'media', 'reactions'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function create(array $data): Message
    {
        return Message::create($data);
    }

    public function markAsRead(int $messageId, int $userId): bool
    {
        $message = Message::find($messageId);
        if (! $message) {
            return false;
        }

        MessageStatus::updateOrCreate(
            ['message_id' => $messageId, 'user_id' => $userId],
            ['status' => 'READ', 'timestamp' => now()]
        );

        return true;
    }
}
