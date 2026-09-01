<?php

namespace App\Http\Controllers\Api\v1\Messaging;

use App\Http\Controllers\Controller;
use App\Http\Resources\Messaging\ConversationResource;
use App\Repositories\Messaging\ConversationRepositoryInterface;
use App\Services\Messaging\ChatEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    protected $chatEngine;

    protected $conversationRepository;

    public function __construct(ChatEngine $chatEngine, ConversationRepositoryInterface $conversationRepository)
    {
        $this->chatEngine = $chatEngine;
        $this->conversationRepository = $conversationRepository;
    }

    public function index()
    {
        $conversations = $this->conversationRepository->getUserConversations(Auth::id());

        return ConversationResource::collection($conversations);
    }

    public function store(Request $request)
    {
        $request->validate([
            'participant_ids' => 'required|array|min:1',
            'type' => 'sometimes|string',
            'name' => 'sometimes|string|max:255',
        ]);

        $conversation = $this->chatEngine->startConversation(
            $request->participant_ids,
            $request->type ?? 'PRIVATE',
            ['name' => $request->name]
        );

        return new ConversationResource($conversation->load('participants'));
    }

    public function show(int $id)
    {
        $conversation = $this->conversationRepository->findById($id);
        if (! $conversation) {
            return response()->json(['message' => 'Conversation not found'], 404);
        }

        return new ConversationResource($conversation->load(['participants', 'lastMessage']));
    }

    public function initiateCall(Request $request, int $id)
    {
        $request->validate(['type' => 'required|in:audio,video']);
        $this->chatEngine->initiateCall($id, $request->type);

        return response()->json(['message' => 'Call initiated']);
    }
}
