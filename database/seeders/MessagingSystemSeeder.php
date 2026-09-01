<?php

namespace Database\Seeders;

use App\Models\Garages\RepairOrder;
use App\Models\Messaging\ChatContext;
use App\Models\Messaging\Conversation;
use App\Models\Messaging\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class MessagingSystemSeeder extends Seeder
{
    public function run(): void
    {
        $eric = User::where('email', 'eric@piiston.com')->first();
        $dave = User::where('email', 'mechanic@piiston.com')->first();
        $client = User::where('email', 'client@piiston.com')->first();
        $ro = RepairOrder::first();

        if (! $eric || ! $dave || ! $client) {
            return;
        }

        // 1. Private Chat: Eric & Dave
        $conv1 = Conversation::create([
            'conversation_type' => 'PRIVATE_CHAT',
            'created_by' => $eric->id,
        ]);
        $conv1->participants()->attach([$eric->id, $dave->id]);

        $m1 = Message::create([
            'conversation_id' => $conv1->id,
            'sender_id' => $dave->id,
            'message_type' => 'TEXT',
            'content' => 'Hello Eric, I am starting the inspection on the Corolla.',
        ]);
        $conv1->update(['last_message_id' => $m1->id]);

        // 2. Contextual Chat: Client & Repair Order
        $conv2 = Conversation::create([
            'conversation_type' => 'REPAIR_CHAT',
            'name' => 'Repair Support: Corolla',
            'created_by' => $dave->id,
        ]);
        $conv2->participants()->attach([$client->id, $dave->id]);

        if ($ro) {
            ChatContext::create([
                'conversation_id' => $conv2->id,
                'context_type' => 'REPAIR_ORDER',
                'context_id' => $ro->id,
            ]);
        }

        $m2 = Message::create([
            'conversation_id' => $conv2->id,
            'sender_id' => $dave->id,
            'message_type' => 'TEXT',
            'content' => 'The turbo hose replacement is completed. We are now in the testing phase.',
        ]);
        $conv2->update(['last_message_id' => $m2->id]);
    }
}
