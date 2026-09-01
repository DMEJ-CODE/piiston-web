<?php

namespace Database\Seeders;

use App\Models\Garages\GarageCompany;
use App\Models\Marketplace\SparePart;
use App\Models\Search\SearchCategory;
use App\Models\Vehicles\Vehicle;
use App\Services\Search\IndexingService;
use Illuminate\Database\Seeder;

class SearchSystemSeeder extends Seeder
{
    public function run(): void
    {
        $indexer = new IndexingService;

        // 1. Create Categories
        $cats = [
            ['name' => 'Garage', 'icon' => 'garage'],
            ['name' => 'Mechanic', 'icon' => 'engineering'],
            ['name' => 'Spare Part', 'icon' => 'settings_input_component'],
            ['name' => 'Vehicle', 'icon' => 'directions_car'],
        ];

        foreach ($cats as $c) {
            SearchCategory::firstOrCreate(['name' => $c['name']], $c);
        }

        // 2. Index existing data
        $garage = GarageCompany::first();
        if ($garage) {
            $indexer->indexModel($garage, 1, $garage->name, $garage->legal_name, $garage->description);
        }

        $part = SparePart::first();
        if ($part) {
            $indexer->indexModel($part, 3, $part->name, $part->part_number, $part->description);
        }

        $vehicle = Vehicle::with(['brand', 'model'])->first();
        if ($vehicle) {
            $indexer->indexModel($vehicle, 4, "{$vehicle->brand->name} {$vehicle->model->name}", $vehicle->license_plate, $vehicle->color);
        }
    }
}
