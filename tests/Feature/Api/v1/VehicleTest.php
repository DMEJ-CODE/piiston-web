<?php

namespace Tests\Feature\Api\v1;

use App\Models\Globalization\Country;
use App\Models\User;
use App\Models\Vehicles\Vehicle;
use App\Models\Vehicles\VehicleBrand;
use App\Models\Vehicles\VehicleModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $country;

    protected $brand;

    protected $model;

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

        $this->brand = VehicleBrand::create(['name' => 'Toyota', 'status' => 'active']);
        $this->model = VehicleModel::create(['brand_id' => $this->brand->id, 'name' => 'Corolla', 'status' => 'active']);
    }

    public function test_user_can_add_vehicle()
    {
        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/vehicles', [
                'brand_id' => $this->brand->id,
                'model_id' => $this->model->id,
                'year' => 2022,
                'license_plate' => 'LT123ABC',
                'country_id' => $this->country->id,
                'mileage' => 5000,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('vehicle.license_plate', 'LT123ABC');

        $this->assertDatabaseHas('vehicles', ['license_plate' => 'LT123ABC']);
    }

    public function test_user_can_list_own_vehicles()
    {
        Vehicle::create([
            'owner_id' => $this->user->id,
            'brand_id' => $this->brand->id,
            'model_id' => $this->model->id,
            'year' => 2020,
            'license_plate' => 'LT456DEF',
            'country_id' => $this->country->id,
            'mileage' => 10000,
            'status' => 'active',
        ]);

        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/vehicles');

        $response->assertStatus(200)
            ->assertJsonCount(1);
    }
}
