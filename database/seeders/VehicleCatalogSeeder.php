<?php

namespace Database\Seeders;

use App\Models\Globalization\Country;
use App\Models\User;
use App\Models\Vehicles\FuelType;
use App\Models\Vehicles\Transmission;
use App\Models\Vehicles\Vehicle;
use App\Models\Vehicles\VehicleBrand;
use App\Models\Vehicles\VehicleCategory;
use App\Models\Vehicles\VehicleEngine;
use App\Models\Vehicles\VehicleGeneration;
use App\Models\Vehicles\VehicleModel;
use Illuminate\Database\Seeder;

class VehicleCatalogSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reference Data
        $categories = ['Car', 'Motorcycle', 'Truck', 'Bus', 'Van', 'SUV'];
        foreach ($categories as $cat) {
            VehicleCategory::firstOrCreate(['name' => $cat]);
        }

        $fuelTypes = ['Petrol', 'Diesel', 'Electric', 'Hybrid', 'Hydrogen'];
        foreach ($fuelTypes as $fuel) {
            FuelType::firstOrCreate(['name' => $fuel]);
        }

        $transmissions = ['Manual', 'Automatic', 'CVT'];
        foreach ($transmissions as $trans) {
            Transmission::firstOrCreate(['name' => $trans]);
        }

        // 2. Catalog Data (Example: Toyota)
        $toyota = VehicleBrand::firstOrCreate(['name' => 'Toyota'], ['country_origin' => 'Japan']);
        $mercedes = VehicleBrand::firstOrCreate(['name' => 'Mercedes-Benz'], ['country_origin' => 'Germany']);
        $tesla = VehicleBrand::firstOrCreate(['name' => 'Tesla'], ['country_origin' => 'USA']);

        $corolla = VehicleModel::firstOrCreate(['brand_id' => $toyota->id, 'name' => 'Corolla'], ['category_id' => 1]);
        $hilux = VehicleModel::firstOrCreate(['brand_id' => $toyota->id, 'name' => 'Hilux'], ['category_id' => 3]);

        $corollaE120 = VehicleGeneration::firstOrCreate([
            'model_id' => $corolla->id,
            'generation_name' => 'E120',
            'start_year' => 2000,
            'end_year' => 2006,
        ]);

        $corollaE210 = VehicleGeneration::firstOrCreate([
            'model_id' => $corolla->id,
            'generation_name' => 'E210',
            'start_year' => 2018,
        ]);

        VehicleEngine::firstOrCreate([
            'generation_id' => $corollaE120->id,
            'engine_code' => '1ZZ-FE',
            'fuel_type' => 'Petrol',
            'capacity' => '1.8L',
            'horsepower' => 130,
        ]);

        // 3. Create a sample vehicle for Eric
        $eric = User::where('email', 'eric@piiston.com')->first();
        $cameroon = Country::where('iso_code', 'CM')->first();

        if ($eric && $cameroon) {
            Vehicle::firstOrCreate(
                ['vin' => 'TOYOTA123456789'],
                [
                    'owner_id' => $eric->id,
                    'country_id' => $cameroon->id,
                    'brand_id' => $toyota->id,
                    'model_id' => $corolla->id,
                    'generation_id' => $corollaE120->id,
                    'year' => 2005,
                    'license_plate' => 'LT-123-AA',
                    'color' => 'Silver',
                    'fuel_type_id' => 1,
                    'transmission_id' => 1,
                    'mileage' => 150000,
                    'status' => 'active',
                ]
            );
        }
    }
}
