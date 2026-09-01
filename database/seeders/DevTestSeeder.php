<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DevTestSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            GlobalizationSeeder::class,
            IdentitySeeder::class,
            VehicleCatalogSeeder::class,
            GarageSystemSeeder::class,
            MarketplaceSystemSeeder::class,
            MechanicSystemSeeder::class,
            WorkflowSystemSeeder::class,
            MessagingSystemSeeder::class,
            NotificationSystemSeeder::class,
        ]);
    }
}
