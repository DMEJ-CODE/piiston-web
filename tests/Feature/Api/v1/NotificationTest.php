<?php

namespace Tests\Feature\Api\v1;

use App\Models\Globalization\Country;
use App\Models\Notifications\Notification;
use App\Models\Notifications\NotificationType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $country;

    protected $type;

    protected function setUp(): void
    {
        parent::setUp();

        $this->country = Country::create([
            'name' => 'Cameroon',
            'iso_code' => 'CM',
            'phone_code' => '+237',
            'status' => 'active',
        ]);

        $this->user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '123456789',
            'password' => bcrypt('password'),
            'country_id' => $this->country->id,
            'status' => 'active',
        ]);

        $this->type = NotificationType::create([
            'name' => 'SYSTEM',
            'category' => 'system',
            'status' => 'active',
        ]);
    }

    public function test_user_can_list_own_notifications()
    {
        Notification::create([
            'user_id' => $this->user->id,
            'type_id' => $this->type->id,
            'title' => 'Test Notification',
            'message' => 'This is a test.',
            'is_read' => false,
        ]);

        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/notifications');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.title', 'Test Notification');
    }

    public function test_user_can_mark_notification_as_read()
    {
        $notification = Notification::create([
            'user_id' => $this->user->id,
            'type_id' => $this->type->id,
            'title' => 'Test Notification',
            'message' => 'This is a test.',
            'is_read' => false,
        ]);

        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson("/api/notifications/{$notification->id}/read");

        $response->assertStatus(200);
        $this->assertTrue($notification->fresh()->is_read);
    }
}
