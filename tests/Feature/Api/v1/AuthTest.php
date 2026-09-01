<?php

namespace Tests\Feature\Api\v1;

use App\Models\Globalization\Country;
use App\Models\Identity\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic data
        Country::create([
            'name' => 'Cameroon',
            'iso_code' => 'CM',
            'phone_code' => '+237',
            'status' => 'active',
        ]);

        Role::create(['name' => 'VEHICLE_OWNER', 'status' => 'active']);
    }

    public function test_user_can_register()
    {
        $country = Country::first();

        $response = $this->postJson('/api/auth/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '123456789',
            'password' => 'password',
            'password_confirmation' => 'password',
            'country_id' => $country->id,
            'role' => 'VEHICLE_OWNER',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user' => ['id', 'first_name', 'last_name', 'email'],
                'access_token',
            ]);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    public function test_user_can_login()
    {
        $country = Country::first();
        $user = User::create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
            'phone' => '987654321',
            'password' => bcrypt('password'),
            'country_id' => $country->id,
            'status' => 'active',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'jane@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token', 'user']);
    }

    public function test_role_middleware_blocks_unauthorized_user()
    {
        $country = Country::create([
            'name' => 'Nigeria',
            'iso_code' => 'NG',
            'phone_code' => '+234',
            'status' => 'active',
        ]);

        $user = User::create([
            'first_name' => 'Regular',
            'last_name' => 'User',
            'email' => 'regular@example.com',
            'phone' => '111222333',
            'password' => bcrypt('password'),
            'country_id' => $country->id,
            'status' => 'active',
        ]);

        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/admin/stats');

        $response->assertStatus(403);
    }
}
