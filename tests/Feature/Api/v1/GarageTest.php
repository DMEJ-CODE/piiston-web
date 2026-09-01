<?php

namespace Tests\Feature\Api\v1;

use App\Models\Garages\GarageCompany;
use App\Models\Globalization\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GarageTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

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

        $this->user = User::create([
            'first_name' => 'Garage',
            'last_name' => 'Owner',
            'email' => 'owner@garage.com',
            'phone' => '123456789',
            'password' => bcrypt('password'),
            'country_id' => $this->country->id,
            'status' => 'active',
        ]);
    }

    public function test_owner_can_register_garage()
    {
        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/garages', [
                'name' => 'Piiston Douala Workshop',
                'legal_name' => 'Piiston Douala Workshop SARL',
                'registration_number' => 'RC/DLA/2026/B/123',
                'email' => 'contact@piiston-dla.com',
                'phone' => '677000000',
                'country_id' => $this->country->id,
                'description' => 'Premium service center.',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('garage.name', 'Piiston Douala Workshop');

        $this->assertDatabaseHas('garage_companies', ['registration_number' => 'RC/DLA/2026/B/123']);
    }

    public function test_public_can_search_garages()
    {
        GarageCompany::create([
            'owner_id' => $this->user->id,
            'country_id' => $this->country->id,
            'name' => 'Toyota Special Center',
            'legal_name' => 'TSC SARL',
            'registration_number' => 'REG001',
            'email' => 'toyota@tsc.com',
            'phone' => '111',
            'status' => 'active',
        ]);

        $response = $this->getJson('/api/garages?q=Toyota');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Toyota Special Center']);
    }
}
