<?php

namespace App\Livewire\Messaging;

use App\Events\Messaging\MessageSent;
use App\Models\Messaging\Conversation;
use App\Services\Messaging\ChatEngine;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ChatCenter extends Component
{
    public $selectedConversationId;

    public $newMessage = '';

    public string $search = '';

    public string $filter = 'All';

    public function getListeners()
    {
        if ($this->selectedConversationId) {
            return [
                "echo-private:conversation.{$this->selectedConversationId},Messaging\MessageSent" => '$refresh',
            ];
        }

        return [];
    }

    public function mount($conversationId = null)
    {
        if ($conversationId) {
            $this->selectedConversationId = $conversationId;
        }
    }

    public function selectConversation($id)
    {
        $this->selectedConversationId = $id;
        $this->newMessage = '';
        app(ChatEngine::class)->markAsRead((int) $id);

        $this->js("window.history.replaceState(null, '', '".route(request()->routeIs('admin.*') ? 'admin.messages.index' : 'garage.messages.index', ['conversationId' => $id])."')");
    }

    public function clearConversation(): void
    {
        $this->selectedConversationId = null;
        $this->newMessage = '';
    }

    public function setFilter(string $filter): void
    {
        $this->filter = in_array($filter, ['All', 'Unread', 'Personal', 'Groups'], true) ? $filter : 'All';
    }

    public function sendMessage()
    {
        if (trim($this->newMessage) === '' || ! $this->selectedConversationId) {
            return;
        }

        $conversation = Conversation::findOrFail($this->selectedConversationId);

        DB::transaction(function () use ($conversation) {
            $message = $conversation->messages()->create([
                'sender_id' => Auth::id(),
                'content' => $this->newMessage,
                'message_type' => 'TEXT',
                'sent_at' => now(),
                'status' => 'SENT',
            ]);

            $conversation->update(['last_message_id' => $message->id]);

            broadcast(new MessageSent($message->load('sender')))->toOthers();
        });

        $this->newMessage = '';
    }

    public function render()
    {
        $conversations = Auth::user()->conversations()
            ->with(['lastMessage', 'participants'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $search = trim($this->search);
        $conversations = $conversations->filter(function ($conversation) use ($search) {
            $other = $conversation->participants->where('id', '!=', Auth::id())->first();
            $name = $conversation->name ?: ($other?->name ?? 'Chat');
            $matchesSearch = $search === '' || str_contains(strtolower($name), strtolower($search)) ||
                str_contains(strtolower((string) $conversation->lastMessage?->content), strtolower($search));
            $isUnread = $conversation->lastMessage && $conversation->lastMessage->sender_id !== Auth::id();
            $matchesFilter = match ($this->filter) {
                'Unread' => $isUnread,
                'Groups' => $conversation->conversation_type === Conversation::TYPE_GROUP,
                'Personal' => $conversation->conversation_type !== Conversation::TYPE_GROUP,
                default => true,
            };

            return $matchesSearch && $matchesFilter;
        });

        $selectedConversation = null;
        $messages = [];

        if ($this->selectedConversationId) {
            $selectedConversation = $conversations->firstWhere('id', $this->selectedConversationId);
            if ($selectedConversation) {
                $messages = $selectedConversation->messages()
                    ->with('sender')
                    ->oldest()
                    ->get();
            }
        }

        return view('livewire.messaging.chat-center', [
            'conversations' => $conversations,
            'selectedConversation' => $selectedConversation,
            'messages' => $messages,
        ]);
    }
}
