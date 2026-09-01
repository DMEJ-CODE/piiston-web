<?php

namespace Database\Seeders;

use App\Models\Notifications\Notification;
use App\Models\Notifications\NotificationTemplate;
use App\Models\Notifications\NotificationType;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create standard notification types
        $types = [
            ['name' => 'ACCOUNT_CREATED', 'category' => 'Account', 'icon' => 'person_add'],
            ['name' => 'REPAIR_COMPLETED', 'category' => 'Repair', 'icon' => 'check_circle'],
            ['name' => 'DIAGNOSIS_COMPLETED', 'category' => 'Repair', 'icon' => 'biotech'],
            ['name' => 'NEW_MESSAGE', 'category' => 'Communication', 'icon' => 'chat'],
            ['name' => 'ORDER_SHIPPED', 'category' => 'Marketplace', 'icon' => 'local_shipping'],
            ['name' => 'MAINTENANCE_DUE', 'category' => 'Fleet', 'icon' => 'warning'],
        ];

        foreach ($types as $t) {
            NotificationType::firstOrCreate(['name' => $t['name']], $t);
        }

        // 2. Create sample templates
        NotificationTemplate::firstOrCreate(['name' => 'repair_ready'], [
            'channel' => 'push',
            'title_template' => 'Repair Completed',
            'body_template' => 'Your vehicle {vehicle_name} is ready for pickup.',
            'variables' => ['vehicle_name'],
        ]);

        // 3. Create test notifications for Eric
        $eric = User::where('email', 'eric@piiston.com')->first();
        if ($eric) {
            $type = NotificationType::where('name', 'REPAIR_COMPLETED')->first();
            Notification::create([
                'user_id' => $eric->id,
                'type_id' => $type->id,
                'title' => 'Repair Completed',
                'message' => 'Your Toyota Corolla is ready for pickup at the Yaoundé Mvan Branch.',
                'priority' => 'high',
                'reference_type' => 'RepairOrder',
                'reference_id' => 1,
            ]);

            $typeMsg = NotificationType::where('name', 'NEW_MESSAGE')->first();
            Notification::create([
                'user_id' => $eric->id,
                'type_id' => $typeMsg->id,
                'title' => 'New Message',
                'message' => 'Dave Mechanic sent you a new message regarding your diagnostics.',
                'priority' => 'medium',
            ]);
        }
    }
}
