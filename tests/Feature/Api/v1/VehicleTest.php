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

    public function test_user_can_update_own_vehicle()
    {
        $vehicle = $this->createVehicle('LT-UPD-001');

        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson('/api/vehicles/'.$vehicle->id, [
                'color' => 'Red',
                'mileage' => 2500,
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Vehicle updated successfully')
            ->assertJsonPath('vehicle.color', 'Red')
            ->assertJsonPath('vehicle.mileage', 2500);

        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'color' => 'Red',
            'mileage' => 2500,
        ]);
    }

    public function test_updating_vehicle_keeps_its_own_unique_plate()
    {
        $vehicle = $this->createVehicle('LT-KEEP-001');

        $token = $this->user->createToken('test')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson('/api/vehicles/'.$vehicle->id, [
                'license_plate' => 'LT-KEEP-001',
                'color' => 'Blue',
            ])
            ->assertStatus(200)
            ->assertJsonPath('vehicle.color', 'Blue');
    }

    public function test_update_rejects_plate_owned_by_another_vehicle()
    {
        $this->createVehicle('LT-TAKEN-001');
        $vehicle = $this->createVehicle('LT-FREE-001');

        $token = $this->user->createToken('test')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson('/api/vehicles/'.$vehicle->id, ['license_plate' => 'LT-TAKEN-001'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('license_plate');
    }

    public function test_update_returns_404_for_unknown_vehicle()
    {
        $token = $this->user->createToken('test')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson('/api/vehicles/999999', ['color' => 'Red'])
            ->assertStatus(404);
    }

    public function test_user_cannot_update_another_users_vehicle()
    {
        $vehicle = $this->createVehicle('LT-OTHER-001');

        $other = User::create([
            'first_name' => 'Eve',
            'last_name' => 'Doe',
            'email' => 'eve@example.com',
            'phone' => '987654321',
            'password' => bcrypt('password'),
            'country_id' => $this->country->id,
            'status' => 'active',
        ]);

        $token = $other->createToken('test')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson('/api/vehicles/'.$vehicle->id, ['color' => 'Red'])
            ->assertStatus(403);
    }

    public function test_user_can_delete_own_vehicle()
    {
        $vehicle = $this->createVehicle('LT-DEL-001');

        $token = $this->user->createToken('test')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/vehicles/'.$vehicle->id)
            ->assertStatus(200)
            ->assertJsonPath('message', 'Vehicle deleted successfully');

        $this->assertDatabaseMissing('vehicles', ['id' => $vehicle->id]);
    }

    public function test_delete_returns_404_for_unknown_vehicle()
    {
        $token = $this->user->createToken('test')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/vehicles/999999')
            ->assertStatus(404);
    }

    public function test_user_cannot_delete_another_users_vehicle()
    {
        $vehicle = $this->createVehicle('LT-OTHER-002');

        $other = User::create([
            'first_name' => 'Eve',
            'last_name' => 'Doe',
            'email' => 'eve2@example.com',
            'phone' => '987654322',
            'password' => bcrypt('password'),
            'country_id' => $this->country->id,
            'status' => 'active',
        ]);

        $token = $other->createToken('test')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/vehicles/'.$vehicle->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('vehicles', ['id' => $vehicle->id]);
    }

    public function test_user_can_update_vehicle_mileage()
    {
        $vehicle = $this->createVehicle('LT-MILE-001', 10000);

        $token = $this->user->createToken('test')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson('/api/vehicles/'.$vehicle->id.'/mileage', ['mileage' => 12500])
            ->assertStatus(200)
            ->assertJsonPath('message', 'Mileage updated successfully');

        $this->assertDatabaseHas('vehicles', ['id' => $vehicle->id, 'mileage' => 12500]);

        $this->assertDatabaseHas('vehicle_usage_logs', [
            'vehicle_id' => $vehicle->id,
            'driver_id' => null,
            'distance_km' => 2500,
            'purpose' => 'MILEAGE_UPDATE',
        ]);
    }

    public function test_user_cannot_update_mileage_of_another_users_vehicle()
    {
        $vehicle = $this->createVehicle('LT-OTHER-003');

        $other = User::create([
            'first_name' => 'Eve',
            'last_name' => 'Doe',
            'email' => 'eve3@example.com',
            'phone' => '987654323',
            'password' => bcrypt('password'),
            'country_id' => $this->country->id,
            'status' => 'active',
        ]);

        $token = $other->createToken('test')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson('/api/vehicles/'.$vehicle->id.'/mileage', ['mileage' => 1])
            ->assertStatus(403);
    }

    protected function createVehicle(string $licensePlate, int $mileage = 5000): Vehicle
    {
        return Vehicle::create([
            'owner_id' => $this->user->id,
            'brand_id' => $this->brand->id,
            'model_id' => $this->model->id,
            'year' => 2020,
            'license_plate' => $licensePlate,
            'country_id' => $this->country->id,
            'mileage' => $mileage,
            'status' => 'active',
        ]);
    }
}
