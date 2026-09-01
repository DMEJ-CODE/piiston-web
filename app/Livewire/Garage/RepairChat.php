<?php

namespace App\Livewire\Garage;

use App\Models\Garages\RepairOrder;
use App\Models\Messaging\Conversation;
use Livewire\Component;

class RepairChat extends Component
{
    public RepairOrder $repair;

    public $newMessage = '';

    public $conversation;

    public function mount(RepairOrder $repair)
    {
        $this->repair = $repair;

        // Find or create conversation for this repair
        $this->conversation = Conversation::firstOrCreate([
            'context_id' => $repair->id,
            'context_type' => 'REPAIR_ORDER',
        ]);

        // Ensure both parties are participants
        $this->conversation->participants()->syncWithoutDetaching([
            auth()->id(),
            $repair->garageCustomer->user_id,
        ]);
    }

    public function sendMessage()
    {
        if (trim($this->newMessage) == '') {
            return;
        }

        $this->conversation->messages()->create([
            'sender_id' => auth()->id(),
            'body' => $this->newMessage,
        ]);

        $this->newMessage = '';
    }

    public function render()
    {
        return view('livewire.garage.repair-chat', [
            'messages' => $this->conversation->messages()->with('sender')->oldest()->get(),
        ]);
    }
}
