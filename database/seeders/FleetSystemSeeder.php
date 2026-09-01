<?php

namespace Database\Seeders;

use App\Models\Fleets\Company;
use App\Models\Fleets\Driver;
use App\Models\Fleets\Fleet;
use App\Models\Fleets\FleetMember;
use App\Models\Fleets\MaintenancePlan;
use App\Models\Globalization\Country;
use App\Models\User;
use App\Models\Vehicles\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FleetSystemSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@piiston.com')->first();
        $client = User::where('email', 'client@piiston.com')->first();
        $cameroon = Country::where('iso_code', 'CM')->first();
        $vehicles = Vehicle::limit(2)->get();

        if (! $admin || ! $cameroon || $vehicles->isEmpty()) {
            return;
        }

        // 1. Logistics Company
        $company = Company::create([
            'owner_id' => $admin->id,
            'country_id' => $cameroon->id,
            'name' => 'Piiston Logistics',
            'industry' => 'Transport',
            'email' => 'logistics@piiston.com',
            'phone' => '+237 600 000 001',
            'verification_status' => 'verified',
        ]);

        // 2. Main Fleet
        $fleet = Fleet::create([
            'company_id' => $company->id,
            'name' => 'City Delivery Fleet',
            'manager_id' => $admin->id,
            'type' => 'DELIVERY',
        ]);

        // 3. Members
        FleetMember::create([
            'fleet_id' => $fleet->id,
            'user_id' => $admin->id,
            'role' => 'FLEET_MANAGER',
        ]);

        // 4. Assign Vehicles
        foreach ($vehicles as $v) {
            $fleet->vehicles()->attach($v->id, ['assigned_date' => now()]);
        }

        // 5. Drivers
        $driver1 = Driver::create([
            'user_id' => $client->id,
            'company_id' => $company->id,
            'license_number' => 'LC-123456',
            'license_category' => 'B',
            'license_expiry_date' => Carbon::now()->addYears(2),
            'experience_years' => 5,
        ]);

        // 6. Maintenance Plan
        MaintenancePlan::create([
            'fleet_id' => $fleet->id,
            'name' => 'Standard Preventive Maintenance',
            'description' => 'Regular check every 5000 km',
            'interval_type' => 'MILEAGE_BASED',
            'interval_value' => 5000,
        ]);
    }
}
