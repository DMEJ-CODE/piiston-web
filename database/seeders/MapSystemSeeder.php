<?php

namespace Database\Seeders;

use App\Models\Maps\GeoPoint;
use App\Models\Maps\MapProvider;
use App\Models\Maps\TrackingSession;
use App\Models\User;
use Illuminate\Database\Seeder;

class MapSystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Setup Default Provider
        MapProvider::firstOrCreate(['provider_code' => 'OSM'], [
            'name' => 'OpenStreetMap',
            'status' => true,
        ]);

        // 2. Sample GeoPoints for Cameroon
        GeoPoint::create(['name' => 'Yaoundé Central Station', 'latitude' => 3.8667, 'longitude' => 11.5167, 'type' => 'Service Center']);
        GeoPoint::create(['name' => 'Douala Port Terminal', 'latitude' => 4.0500, 'longitude' => 9.7000, 'type' => 'Logistics Hub']);

        // 3. Simulated Live Session for Dave Mechanic
        $mechanic = User::where('email', 'mechanic@piiston.com')->first();
        if ($mechanic && $mechanic->mechanicProfile) {
            $session = TrackingSession::create([
                'entity_type' => 'Mechanic',
                'entity_id' => $mechanic->mechanicProfile->id,
                'started_by' => $mechanic->id,
                'status' => 'active',
                'started_at' => now(),
            ]);

            $session->liveLocation()->create([
                'latitude' => 3.8480,
                'longitude' => 11.5021,
                'speed' => 35.5,
                'captured_at' => now(),
            ]);
        }
    }
}
