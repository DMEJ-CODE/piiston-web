<?php

namespace Database\Seeders;

use App\Models\Notifications\NotificationType;
use Illuminate\Database\Seeder;

class ActivityNotificationTypesSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'VEHICLE_ADDED',
                'category' => 'Fleet',
                'description' => 'A new vehicle has been added to your profile.',
                'icon' => 'directions_car',
            ],
            [
                'name' => 'APPOINTMENT_BOOKED',
                'category' => 'Garage',
                'description' => 'A new appointment has been scheduled.',
                'icon' => 'event',
            ],
            [
                'name' => 'SOS_REQUESTED',
                'category' => 'Safety',
                'description' => 'An emergency assistance request has been sent.',
                'icon' => 'bolt',
            ],
            [
                'name' => 'SOS_CANCELLED',
                'category' => 'Safety',
                'description' => 'Your emergency assistance request has been cancelled.',
                'icon' => 'cancel',
            ],
            [
                'name' => 'ORDER_PLACED',
                'category' => 'Marketplace',
                'description' => 'A new spare parts order has been placed.',
                'icon' => 'shopping_bag',
            ],
        ];

        foreach ($types as $type) {
            NotificationType::firstOrCreate(['name' => $type['name']], $type);
        }
    }
}
