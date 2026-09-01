<?php

namespace App\Repositories\Messaging;

use App\Models\Messaging\Conversation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentConversationRepository implements ConversationRepositoryInterface
{
    public function findById(int $id): ?Conversation
    {
        return Conversation::with(['participants', 'lastMessage'])->find($id);
    }

    public function getUserConversations(int $userId): Collection
    {
        return Conversation::whereHas('participants', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with(['participants', 'lastMessage'])->orderBy('updated_at', 'desc')->get();
    }

    public function create(array $data, array $participantIds): Conversation
    {
        return DB::transaction(function () use ($data, $participantIds) {
            $conversation = Conversation::create($data);

            foreach ($participantIds as $userId) {
                $conversation->participants()->attach($userId, [
                    'role' => $userId == ($data['created_by'] ?? null) ? 'ADMIN' : 'MEMBER',
                    'joined_at' => now(),
                    'is_admin' => $userId == ($data['created_by'] ?? null),
                ]);
            }

            return $conversation;
        });
    }

    public function addParticipant(int $conversationId, int $userId, array $data = []): bool
    {
        $conversation = Conversation::find($conversationId);
        if (! $conversation) {
            return false;
        }

        $conversation->participants()->syncWithoutDetaching([$userId => array_merge([
            'role' => 'MEMBER',
            'joined_at' => now(),
        ], $data)]);

        return true;
    }

    public function removeParticipant(int $conversationId, int $userId): bool
    {
        $conversation = Conversation::find($conversationId);
        if (! $conversation) {
            return false;
        }

        $conversation->participants()->detach($userId);

        return true;
    }

    public function updateLastMessage(int $conversationId, int $messageId): bool
    {
        return Conversation::where('id', $conversationId)->update(['last_message_id' => $messageId]);
    }
}
