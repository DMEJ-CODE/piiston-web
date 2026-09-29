<?php

namespace App\Livewire\Garage;

use App\Events\Messaging\MessageSent;
use App\Models\Garages\RepairOrder;
use App\Models\Messaging\ChatContext;
use App\Models\Messaging\Conversation;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RepairChat extends Component
{
    public RepairOrder $repair;

    public $newMessage = '';

    public $conversation;

    public function getListeners()
    {
        if ($this->conversation) {
            return [
                "echo-private:conversation.{$this->conversation->id},Messaging\MessageSent" => '$refresh',
            ];
        }

        return [];
    }

    public function mount(RepairOrder $repair)
    {
        $this->repair = $repair;

        // Find or create conversation for this repair using ChatContext
        $context = ChatContext::where('context_type', 'REPAIR_ORDER')
            ->where('context_id', $repair->id)
            ->first();

        if ($context) {
            $this->conversation = $context->conversation;
        } else {
            $this->conversation = Conversation::create([
                'conversation_type' => 'REPAIR_CHAT',
                'name' => 'Discussion - Réparation #'.$repair->id,
                'created_by' => $repair->assigned_mechanic_id ?? auth()->id() ?? 1,
            ]);

            ChatContext::create([
                'conversation_id' => $this->conversation->id,
                'context_type' => 'REPAIR_ORDER',
                'context_id' => $repair->id,
            ]);
        }

        // Ensure both parties are participants
        if ($repair->garageCustomer && $repair->garageCustomer->user_id) {
            $this->conversation->participants()->syncWithoutDetaching(array_filter([
                auth()->id(),
                $repair->garageCustomer->user_id,
            ]));
        }
    }

    public function sendMessage()
    {
        if (trim($this->newMessage) == '') {
            return;
        }

        DB::transaction(function () {
            $message = $this->conversation->messages()->create([
                'sender_id' => auth()->id(),
                'content' => $this->newMessage,
                'message_type' => 'TEXT',
                'sent_at' => now(),
                'status' => 'SENT',
            ]);

            $this->conversation->update(['last_message_id' => $message->id]);

            broadcast(new MessageSent($message->load('sender')))->toOthers();
        });

        $this->newMessage = '';
    }

    public function render()
    {
        return view('livewire.garage.repair-chat', [
            'messages' => $this->conversation->messages()->with('sender')->oldest()->get(),
        ]);
    }
}
