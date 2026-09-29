<?php

namespace App\Services\Messaging;

use App\Events\Messaging\CallStarted;
use App\Events\Messaging\MessageSent;
use App\Models\Messaging\Conversation;
use App\Models\Messaging\Message;
use App\Repositories\Messaging\ConversationRepositoryInterface;
use App\Repositories\Messaging\MessageRepositoryInterface;
use App\Services\Notifications\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatEngine
{
    protected $conversationRepository;

    protected $messageRepository;

    protected $notificationService;

    public function __construct(
        ConversationRepositoryInterface $conversationRepository,
        MessageRepositoryInterface $messageRepository,
        NotificationService $notificationService
    ) {
        $this->conversationRepository = $conversationRepository;
        $this->messageRepository = $messageRepository;
        $this->notificationService = $notificationService;
    }

    public function startConversation(array $participantIds, string $type = Conversation::TYPE_PRIVATE, array $meta = []): Conversation
    {
        return $this->conversationRepository->create([
            'conversation_type' => $type,
            'name' => $meta['name'] ?? null,
            'created_by' => Auth::id(),
        ], array_unique(array_merge($participantIds, [Auth::id()])));
    }

    public function sendMessage(int $conversationId, array $data): Message
    {
        return DB::transaction(function () use ($conversationId, $data) {
            $message = $this->messageRepository->create($data + [
                'conversation_id' => $conversationId,
                'sender_id' => Auth::id(),
                'sent_at' => now(),
                'status' => 'SENT',
            ]);

            $this->conversationRepository->updateLastMessage($conversationId, $message->id);

            broadcast(new MessageSent($message->load('sender')))->toOthers();

            // Notify Participants
            $conversation = $this->conversationRepository->findById($conversationId);
            $sender = Auth::user();
            foreach ($conversation->participants as $participant) {
                if ($participant->id !== $sender->id) {
                    $this->notificationService->send(
                        $participant,
                        'NEW_MESSAGE',
                        "Message de {$sender->getNameAttribute()}",
                        $message->content,
                        ['reference_type' => 'Conversation', 'reference_id' => $conversationId, 'category' => 'Message']
                    );
                }
            }

            return $message;
        });
    }

    public function markAsRead(int $conversationId)
    {
        $conversation = $this->conversationRepository->findById($conversationId);
        if (! $conversation) {
            return;
        }

        $lastMessage = $conversation->lastMessage;
        if ($lastMessage) {
            $this->messageRepository->markAsRead($lastMessage->id, Auth::id());
        }

        // Update pivot
        $conversation->participants()->updateExistingPivot(Auth::id(), [
            'last_seen_message_id' => $lastMessage?->id,
        ]);
    }

    public function initiateCall(int $conversationId, string $type)
    {
        $conversation = $this->conversationRepository->findById($conversationId);
        if ($conversation) {
            broadcast(new CallStarted($conversation, $type))->toOthers();
        }
    }
}
