<?php

namespace Tests\Feature\Api\v1;

use App\Models\Garages\GarageCompany;
use App\Models\Garages\RepairPart;
use App\Models\Globalization\Country;
use App\Models\Identity\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GarageCrudTest extends TestCase
{
    use RefreshDatabase;

    protected $owner;

    protected $otherUser;

    protected $country;

    protected $garage;

    protected $branch;

    protected function setUp(): void
    {
        parent::setUp();

        $this->country = Country::create([
            'name' => 'Cameroon',
            'iso_code' => 'CM',
            'phone_code' => '+237',
            'status' => 'active',
        ]);

        $this->owner = User::create([
            'first_name' => 'Garage',
            'last_name' => 'Owner',
            'email' => 'garage-owner@example.com',
            'phone' => '123456789',
            'password' => bcrypt('password'),
            'country_id' => $this->country->id,
            'status' => 'active',
        ]);

        $this->otherUser = User::create([
            'first_name' => 'Other',
            'last_name' => 'User',
            'email' => 'other@example.com',
            'phone' => '987654321',
            'password' => bcrypt('password'),
            'country_id' => $this->country->id,
            'status' => 'active',
        ]);

        $adminRole = Role::create(['name' => 'ADMIN', 'status' => 'active']);
        $this->owner->roles()->attach($adminRole->id, ['assigned_at' => now(), 'status' => 'active']);

        $this->garage = GarageCompany::create([
            'owner_id' => $this->owner->id,
            'country_id' => $this->country->id,
            'name' => 'Test Garage',
            'email' => 'garage@test.com',
            'phone' => '111',
            'status' => 'active',
        ]);

        $this->branch = $this->garage->branches()->create([
            'name' => 'Douala Branch',
            'phone' => '222',
            'email' => 'dla@test.com',
            'status' => 'active',
        ]);
    }

    private function authHeader(User $user): array
    {
        return ['Authorization' => 'Bearer '.$user->createToken('test')->plainTextToken];
    }

    public function test_other_user_cannot_access_garage_branches()
    {
        $response = $this->withHeaders($this->authHeader($this->otherUser))
            ->getJson("/api/garage/companies/{$this->garage->id}/branches");

        $response->assertStatus(403);
    }

    public function test_owner_can_create_customer()
    {
        $response = $this->withHeaders($this->authHeader($this->owner))
            ->postJson("/api/garage/branches/{$this->branch->id}/customers", [
                'customer_type' => 'INDIVIDUAL',
            ]);

        $response->assertStatus(201);
    }

    public function test_owner_can_create_repair_part()
    {
        $response = $this->withHeaders($this->authHeader($this->owner))
            ->postJson("/api/garage/branches/{$this->branch->id}/parts", [
                'name' => 'Oil Filter',
                'selling_price' => 5000,
                'stock_quantity' => 10,
            ]);

        $response->assertStatus(201);
    }

    public function test_owner_can_view_low_stock_parts()
    {
        RepairPart::create([
            'branch_id' => $this->branch->id,
            'name' => 'Brake Pad',
            'selling_price' => 3000,
            'stock_quantity' => 1,
            'minimum_stock' => 5,
        ]);

        $response = $this->withHeaders($this->authHeader($this->owner))
            ->getJson("/api/garage/branches/{$this->branch->id}/parts/low-stock");

        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }
}
