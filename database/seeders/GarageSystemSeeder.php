<?php

namespace Database\Seeders;

use App\Models\Garages\GarageBranch;
use App\Models\Garages\GarageCompany;
use App\Models\Garages\GarageCustomer;
use App\Models\Garages\GarageDepartment;
use App\Models\Garages\GarageEmployee;
use App\Models\Garages\GarageService;
use App\Models\Garages\RepairOrder;
use App\Models\Garages\VehicleCheckIn;
use App\Models\Garages\WorkshopBay;
use App\Models\Globalization\Country;
use App\Models\User;
use App\Models\Vehicles\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class GarageSystemSeeder extends Seeder
{
    public function run(): void
    {
        $eric = User::where('email', 'eric@piiston.com')->first();
        $cameroon = Country::where('iso_code', 'CM')->first();
        $mechanic = User::where('email', 'mechanic@piiston.com')->first();
        $client = User::where('email', 'client@piiston.com')->first();
        $vehicle = Vehicle::first();

        if (! $eric || ! $cameroon) {
            return;
        }

        // 1. Garage Company
        $company = GarageCompany::create([
            'owner_id' => $eric->id,
            'country_id' => $cameroon->id,
            'name' => 'Toyota Service Center',
            'legal_name' => 'Toyota Cameroon SA',
            'email' => 'contact@toyota.cm',
            'phone' => '+237 222 333 444',
            'verification_status' => 'verified',
        ]);

        // 2. Branches
        $branchYde = GarageBranch::create([
            'company_id' => $company->id,
            'name' => 'Yaoundé Mvan Branch',
            'email' => 'yde-mvan@toyota.cm',
            'phone' => '+237 677 000 111',
        ]);

        $branchDla = GarageBranch::create([
            'company_id' => $company->id,
            'name' => 'Douala Akwa Branch',
            'email' => 'dla-akwa@toyota.cm',
            'phone' => '+237 677 000 222',
        ]);

        // 3. Departments
        $deptMech = GarageDepartment::create(['branch_id' => $branchYde->id, 'name' => 'Mechanical Repair']);
        $deptElec = GarageDepartment::create(['branch_id' => $branchYde->id, 'name' => 'Electrical System']);

        // 4. Employees
        if ($mechanic) {
            GarageEmployee::create([
                'branch_id' => $branchYde->id,
                'user_id' => $mechanic->id,
                'department_id' => $deptMech->id,
                'employee_number' => 'EMP-001',
                'position' => 'MECHANIC',
                'hire_date' => Carbon::now()->subYear(),
            ]);
        }

        // 5. Services
        GarageService::create([
            'branch_id' => $branchYde->id,
            'name' => 'Premium Oil Change',
            'duration_minutes' => 45,
            'price' => 25000,
        ]);

        // 6. Bays
        WorkshopBay::create(['branch_id' => $branchYde->id, 'name' => 'Bay 1', 'type' => 'Mechanical']);
        WorkshopBay::create(['branch_id' => $branchYde->id, 'name' => 'Bay 2', 'type' => 'Diagnostic']);

        // 7. Workflow Example (Reception -> RO)
        if ($client && $vehicle) {
            $customer = GarageCustomer::create([
                'branch_id' => $branchYde->id,
                'user_id' => $client->id,
                'customer_type' => 'Individual',
            ]);

            $checkIn = VehicleCheckIn::create([
                'branch_id' => $branchYde->id,
                'vehicle_id' => $vehicle->id,
                'customer_id' => $customer->id,
                'received_by' => $eric->id,
                'arrival_date' => Carbon::now(),
                'mileage' => 150000,
                'fuel_level' => '75%',
                'status' => 'INSPECTION',
            ]);

            RepairOrder::create([
                'branch_id' => $branchYde->id,
                'vehicle_id' => $vehicle->id,
                'customer_id' => $customer->id,
                'problem_description' => 'Unusual noise in the front left wheel.',
                'status' => 'OPEN',
                'priority' => 'high',
            ]);
        }
    }
}
