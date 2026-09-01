<?php

namespace Tests\Feature\Api\v1;

use App\Models\Garages\GarageCompany;
use App\Models\Garages\RepairPart;
use App\Models\Globalization\Country;
use App\Models\Identity\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GarageApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

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

        $this->user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'garage@example.com',
            'phone' => '123456789',
            'password' => bcrypt('password'),
            'country_id' => $this->country->id,
            'status' => 'active',
        ]);

        $adminRole = Role::create(['name' => 'ADMIN', 'status' => 'active']);
        $this->user->roles()->attach($adminRole->id, ['assigned_at' => now(), 'status' => 'active']);

        $this->garage = GarageCompany::create([
            'owner_id' => $this->user->id,
            'country_id' => $this->country->id,
            'name' => 'Test Garage',
            'legal_name' => 'Test Garage SARL',
            'registration_number' => 'REG001',
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

    public function test_user_can_view_branches()
    {
        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson("/api/garage/companies/{$this->garage->id}/branches");

        $response->assertStatus(200);
    }

    public function test_user_can_create_branch()
    {
        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson("/api/garage/companies/{$this->garage->id}/branches", [
                'name' => 'Yaounde Branch',
                'phone' => '333',
                'email' => 'yde@test.com',
                'status' => 'active',
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('garage_branches', [
            'company_id' => $this->garage->id,
            'name' => 'Yaounde Branch',
        ]);
    }

    public function test_user_can_view_dashboard()
    {
        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson("/api/garage/dashboard/branch/{$this->branch->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure(['stats']);
    }

    public function test_user_can_list_repair_parts()
    {
        RepairPart::create([
            'branch_id' => $this->branch->id,
            'name' => 'Oil Filter',
            'selling_price' => 5000,
            'stock_quantity' => 10,
        ]);

        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson("/api/garage/branches/{$this->branch->id}/parts");

        $response->assertStatus(200);
    }
}
