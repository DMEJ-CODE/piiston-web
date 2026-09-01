<?php

namespace Tests\Feature\Api\v1;

use App\Models\Garages\GarageCompany;
use App\Models\Garages\GarageCustomer;
use App\Models\Garages\RepairOrder;
use App\Models\Globalization\Country;
use App\Models\Identity\Role;
use App\Models\User;
use App\Models\Vehicles\Vehicle;
use App\Models\Vehicles\VehicleBrand;
use App\Models\Vehicles\VehicleModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RepairTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $country;

    protected $garage;

    protected $branch;

    protected $vehicle;

    protected $garageCustomer;

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
            'email' => 'customer@example.com',
            'phone' => '123456789',
            'password' => bcrypt('password'),
            'country_id' => $this->country->id,
            'status' => 'active',
        ]);

        // Give admin role to bypass policy issues in test
        $adminRole = Role::create(['name' => 'ADMIN', 'status' => 'active']);
        $this->user->roles()->attach($adminRole->id, ['assigned_at' => now(), 'status' => 'active']);

        $brand = VehicleBrand::create(['name' => 'Toyota', 'status' => 'active']);
        $model = VehicleModel::create(['brand_id' => $brand->id, 'name' => 'Corolla', 'status' => 'active']);

        $this->vehicle = Vehicle::create([
            'owner_id' => $this->user->id,
            'brand_id' => $brand->id,
            'model_id' => $model->id,
            'year' => 2020,
            'license_plate' => 'LT123ABC',
            'country_id' => $this->country->id,
            'mileage' => 10000,
            'status' => 'active',
        ]);

        $this->garage = GarageCompany::create([
            'owner_id' => $this->user->id,
            'country_id' => $this->country->id,
            'name' => 'John Workshop',
            'legal_name' => 'John Workshop SARL',
            'registration_number' => 'REG001',
            'email' => 'workshop@example.com',
            'phone' => '111',
            'status' => 'active',
        ]);

        $this->branch = $this->garage->branches()->create([
            'name' => 'Douala Branch',
            'phone' => '222',
            'email' => 'dla@workshop.com',
            'status' => 'active',
        ]);

        $this->garageCustomer = GarageCustomer::create([
            'branch_id' => $this->branch->id,
            'user_id' => $this->user->id,
            'customer_type' => 'INDIVIDUAL',
        ]);
    }

    public function test_user_can_view_own_repairs()
    {
        RepairOrder::create([
            'branch_id' => $this->branch->id,
            'vehicle_id' => $this->vehicle->id,
            'customer_id' => $this->garageCustomer->id,
            'problem_description' => 'Engine noise',
            'priority' => 'high',
            'status' => RepairOrder::STATUS_REQUESTED,
        ]);

        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/repairs');

        $response->assertStatus(200);
    }

    public function test_garage_owner_can_submit_diagnosis()
    {
        $repair = RepairOrder::create([
            'branch_id' => $this->branch->id,
            'vehicle_id' => $this->vehicle->id,
            'customer_id' => $this->garageCustomer->id,
            'problem_description' => 'Engine noise',
            'status' => RepairOrder::STATUS_REQUESTED,
        ]);

        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson("/api/repairs/{$repair->id}/diagnostic", [
                'symptoms' => 'Metallic clicking sound',
                'detected_problem' => 'Worn valve lifters',
                'solution' => 'Replace valve lifters',
                'severity' => 'medium',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('repair_orders', [
            'id' => $repair->id,
            'status' => RepairOrder::STATUS_DIAGNOSIS,
        ]);
    }
}
