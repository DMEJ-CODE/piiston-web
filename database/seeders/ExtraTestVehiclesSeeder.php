<?php

namespace Database\Seeders;

use App\Models\Globalization\Country;
use App\Models\User;
use App\Models\Vehicles\Vehicle;
use App\Models\Vehicles\VehicleBrand;
use App\Models\Vehicles\VehicleModel;
use Illuminate\Database\Seeder;

class ExtraTestVehiclesSeeder extends Seeder
{
    public function run(): void
    {
        $client = User::where('email', 'client@piiston.com')->first();
        $toyota = VehicleBrand::where('name', 'Toyota')->first();
        $mercedes = VehicleBrand::where('name', 'Mercedes-Benz')->first();
        $cameroon = Country::where('iso_code', 'CM')->first();

        if ($client && $toyota && $cameroon) {
            Vehicle::create([
                'owner_id' => $client->id,
                'country_id' => $cameroon->id,
                'brand_id' => $toyota->id,
                'model_id' => VehicleModel::where('brand_id', $toyota->id)->first()->id,
                'year' => 2021,
                'license_plate' => 'CE-991-XX',
                'mileage' => 45000,
                'status' => 'active',
                'vin' => 'VIN'.rand(100000, 999999),
            ]);
        }

        if ($client && $mercedes && $cameroon) {
            Vehicle::create([
                'owner_id' => $client->id,
                'country_id' => $cameroon->id,
                'brand_id' => $mercedes->id,
                'model_id' => VehicleModel::where('brand_id', $mercedes->id)->first() ? VehicleModel::where('brand_id', $mercedes->id)->first()->id : 1,
                'year' => 2023,
                'license_plate' => 'LT-007-BZ',
                'mileage' => 12000,
                'status' => 'active',
                'vin' => 'VIN'.rand(100000, 999999),
            ]);
        }
    }
}
