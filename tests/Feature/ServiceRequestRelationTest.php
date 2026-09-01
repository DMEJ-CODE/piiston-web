<?php

namespace Tests\Feature;

use App\Models\Garages\GarageAppointment;
use App\Models\Garages\GarageCompany;
use App\Models\Garages\GarageCustomer;
use App\Models\Garages\RepairOrder;
use App\Models\Globalization\Country;
use App\Models\User;
use App\Models\Vehicles\Vehicle;
use App\Models\Vehicles\VehicleBrand;
use App\Models\Vehicles\VehicleModel;
use App\Models\Workflows\ServiceRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceRequestRelationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $country = Country::create([
            'name' => 'Cameroon',
            'iso_code' => 'CM',
            'phone_code' => '+237',
            'status' => 'active',
        ]);

        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'client@example.com',
            'phone' => '123456789',
            'password' => bcrypt('password'),
            'country_id' => $country->id,
            'status' => 'active',
        ]);

        $brand = VehicleBrand::create(['name' => 'Toyota', 'status' => 'active']);
        $model = VehicleModel::create(['brand_id' => $brand->id, 'name' => 'Corolla', 'status' => 'active']);

        $vehicle = Vehicle::create([
            'owner_id' => $user->id,
            'brand_id' => $brand->id,
            'model_id' => $model->id,
            'year' => 2020,
            'license_plate' => 'LT123ABC',
            'country_id' => $country->id,
            'mileage' => 10000,
            'status' => 'active',
        ]);

        $this->garage = GarageCompany::create([
            'owner_id' => $user->id,
            'country_id' => $country->id,
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

        $this->customer = GarageCustomer::create([
            'branch_id' => $this->branch->id,
            'user_id' => $user->id,
            'customer_type' => 'INDIVIDUAL',
        ]);

        $this->serviceRequest = ServiceRequest::create([
            'user_id' => $user->id,
            'vehicle_id' => $vehicle->id,
            'request_type' => 'REPAIR',
            'description' => 'Engine noise',
            'priority' => 'high',
            'status' => 'PENDING',
        ]);

        $this->appointment = GarageAppointment::create([
            'branch_id' => $this->branch->id,
            'customer_id' => $this->customer->id,
            'vehicle_id' => $vehicle->id,
            'request_id' => $this->serviceRequest->id,
            'scheduled_date' => now()->addDay(),
            'status' => 'SCHEDULED',
        ]);

        $this->repairOrder = RepairOrder::create([
            'branch_id' => $this->branch->id,
            'vehicle_id' => $vehicle->id,
            'customer_id' => $this->customer->id,
            'appointment_id' => $this->appointment->id,
            'problem_description' => 'Engine noise',
            'status' => RepairOrder::STATUS_REQUESTED,
        ]);
    }

    public function test_service_request_can_access_repair_order_through_appointment()
    {
        $this->assertNotNull($this->serviceRequest->repairOrder);
        $this->assertEquals($this->repairOrder->id, $this->serviceRequest->repairOrder->id);
    }
}
