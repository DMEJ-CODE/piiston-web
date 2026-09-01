<?php

namespace Tests\Feature\Api\v1;

use App\Models\Fleets\Company;
use App\Models\Fleets\Fleet;
use App\Models\Globalization\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FleetTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $country;

    protected $company;

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
            'first_name' => 'Fleet',
            'last_name' => 'Manager',
            'email' => 'manager@fleet.com',
            'phone' => '123456789',
            'password' => bcrypt('password'),
            'country_id' => $this->country->id,
            'status' => 'active',
        ]);

        $this->company = Company::create([
            'owner_id' => $this->user->id,
            'country_id' => $this->country->id,
            'name' => 'Piiston Logistics',
            'legal_name' => 'Logistics SARL',
            'registration_number' => 'LOG001',
            'status' => 'active',
        ]);
    }

    public function test_manager_can_create_fleet()
    {
        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/fleets', [
                'company_id' => $this->company->id,
                'name' => 'Douala Delivery',
                'description' => 'Local logistics fleet.',
                'manager_id' => $this->user->id,
                'type' => 'DELIVERY',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('fleet.name', 'Douala Delivery');

        $this->assertDatabaseHas('fleets', ['name' => 'Douala Delivery']);
    }

    public function test_can_list_company_fleets()
    {
        Fleet::create([
            'company_id' => $this->company->id,
            'name' => 'Regional Transport',
            'manager_id' => $this->user->id,
            'type' => 'TRANSPORT',
            'status' => 'active',
        ]);

        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson("/api/fleets?company_id={$this->company->id}");

        $response->assertStatus(200)
            ->assertJsonCount(1);
    }
}
