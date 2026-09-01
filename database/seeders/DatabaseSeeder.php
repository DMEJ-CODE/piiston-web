<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            GlobalizationSeeder::class,
            IdentitySeeder::class,
            AdministrationSystemSeeder::class,
            DocumentSystemSeeder::class,
            VehicleCatalogSeeder::class,
            MarketplaceSystemSeeder::class,
            DemoSeeder::class,
        ]);

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            User::factory()->make(['name' => 'Test User'])->toArray()
        );
    }
}
