<?php

namespace App\Repositories\Messaging;

use App\Models\Messaging\Conversation;
use Illuminate\Support\Collection;

interface ConversationRepositoryInterface
{
    public function findById(int $id): ?Conversation;

    public function getUserConversations(int $userId): Collection;

    public function create(array $data, array $participantIds): Conversation;

    public function addParticipant(int $conversationId, int $userId, array $data = []): bool;

    public function removeParticipant(int $conversationId, int $userId): bool;

    public function updateLastMessage(int $conversationId, int $messageId): bool;
}
