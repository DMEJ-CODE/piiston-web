<?php

namespace App\Http\Controllers\Api\v1\Messaging;

use App\Http\Controllers\Controller;
use App\Http\Resources\Messaging\MessageResource;
use App\Repositories\Messaging\MessageRepositoryInterface;
use App\Services\Messaging\ChatEngine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected $chatEngine;

    protected $messageRepository;

    public function __construct(ChatEngine $chatEngine, MessageRepositoryInterface $messageRepository)
    {
        $this->chatEngine = $chatEngine;
        $this->messageRepository = $messageRepository;
    }

    public function index(int $conversationId)
    {
        $messages = $this->messageRepository->getConversationMessages($conversationId);

        return MessageResource::collection($messages);
    }

    public function store(Request $request, int $conversationId)
    {
        $request->validate([
            'content' => 'required_without:text|string',
            'text' => 'required_without:content|string',
            'type' => 'sometimes|string',
        ]);

        $message = $this->chatEngine->sendMessage($conversationId, [
            'content' => $request->content ?? $request->text,
            'message_type' => $request->type ?? 'TEXT',
        ]);

        return new MessageResource($message->load('sender'));
    }

    public function markAsRead(int $conversationId): JsonResponse
    {
        $this->chatEngine->markAsRead($conversationId);

        return response()->json(['message' => 'Messages marked as read']);
    }
}
