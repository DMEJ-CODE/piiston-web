<?php

namespace Tests\Feature\Api\v1;

use App\Models\Globalization\Country;
use App\Models\Messaging\Conversation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagingTest extends TestCase
{
    use RefreshDatabase;

    protected $user1;

    protected $user2;

    protected function setUp(): void
    {
        parent::setUp();

        $country = Country::create(['name' => 'Cameroon', 'iso_code' => 'CM', 'phone_code' => '+237', 'status' => 'active']);

        $this->user1 = User::create([
            'first_name' => 'User', 'last_name' => 'One', 'email' => 'user1@example.com', 'phone' => '111',
            'password' => bcrypt('password'), 'country_id' => $country->id, 'status' => 'active',
        ]);

        $this->user2 = User::create([
            'first_name' => 'User', 'last_name' => 'Two', 'email' => 'user2@example.com', 'phone' => '222',
            'password' => bcrypt('password'), 'country_id' => $country->id, 'status' => 'active',
        ]);
    }

    public function test_user_can_start_conversation()
    {
        $this->actingAs($this->user1);

        $response = $this->postJson('/api/conversations', [
            'participant_ids' => [$this->user2->id],
            'type' => 'PRIVATE',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.type', 'PRIVATE');

        $this->assertDatabaseHas('conversations', ['created_by' => $this->user1->id]);
    }

    public function test_user_can_send_message()
    {
        $this->actingAs($this->user1);

        $conversation = Conversation::create([
            'conversation_type' => 'PRIVATE',
            'created_by' => $this->user1->id,
        ]);
        $conversation->participants()->attach([$this->user1->id, $this->user2->id]);

        $response = $this->postJson("/api/conversations/{$conversation->id}/messages", [
            'content' => 'Hello World',
            'type' => 'TEXT',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.content', 'Hello World');

        $this->assertDatabaseHas('messages', ['content' => 'Hello World']);
    }
}
