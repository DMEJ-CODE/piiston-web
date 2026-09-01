<?php

namespace App\Repositories\Messaging;

use App\Models\Messaging\Message;
use Illuminate\Pagination\LengthAwarePaginator;

interface MessageRepositoryInterface
{
    public function findById(int $id): ?Message;

    public function getConversationMessages(int $conversationId, int $perPage = 30): LengthAwarePaginator;

    public function create(array $data): Message;

    public function markAsRead(int $messageId, int $userId): bool;
}
