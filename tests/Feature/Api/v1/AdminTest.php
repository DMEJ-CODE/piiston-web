<?php

namespace Tests\Feature\Api\v1;

use App\Models\Administration\Administrator;
use App\Models\Globalization\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected $regularUser;

    protected $country;

    protected function setUp(): void
    {
        parent::setUp();

        $this->country = Country::create([
            'name' => 'Cameroon',
            'iso_code' => 'CM',
            'phone_code' => '+237',
            'status' => 'active',
        ]);

        $this->adminUser = User::create([
            'first_name' => 'Platform',
            'last_name' => 'Admin',
            'email' => 'admin@piiston.com',
            'phone' => '123456789',
            'password' => bcrypt('password'),
            'country_id' => $this->country->id,
            'status' => 'active',
        ]);

        Administrator::create([
            'user_id' => $this->adminUser->id,
            'employee_number' => 'ADM-001',
            'position' => 'Super Admin',
            'status' => 'active',
        ]);

        $this->regularUser = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '987654321',
            'password' => bcrypt('password'),
            'country_id' => $this->country->id,
            'status' => 'active',
        ]);
    }

    public function test_admin_can_list_users()
    {
        $token = $this->adminUser->createToken('admin')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/admin/users');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'links', 'meta']);
    }

    public function test_non_admin_cannot_list_users()
    {
        $token = $this->regularUser->createToken('user')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/admin/users');

        $response->assertStatus(403);
    }

    public function test_admin_can_update_user_status()
    {
        $token = $this->adminUser->createToken('admin')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson("/api/admin/users/{$this->regularUser->id}/status", [
                'status' => 'banned',
            ]);

        $response->assertStatus(200);
        $this->assertEquals('banned', $this->regularUser->fresh()->status);
    }
}
