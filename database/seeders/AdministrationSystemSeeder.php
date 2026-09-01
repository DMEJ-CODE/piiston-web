<?php

namespace Database\Seeders;

use App\Models\Administration\Administrator;
use App\Models\Administration\AdminPermission;
use App\Models\Administration\AdminRole;
use App\Models\Administration\FeatureFlag;
use App\Models\Administration\SystemSetting;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdministrationSystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin Roles
        $roles = [
            ['name' => 'SUPER_ADMIN', 'description' => 'Full access to everything.', 'level' => 10],
            ['name' => 'SUPPORT_ADMIN', 'description' => 'Manage users and moderation.', 'level' => 5],
            ['name' => 'FINANCE_ADMIN', 'description' => 'Manage payments and reports.', 'level' => 5],
            ['name' => 'CONTENT_ADMIN', 'description' => 'Manage CMS and FAQs.', 'level' => 3],
        ];

        foreach ($roles as $r) {
            AdminRole::firstOrCreate(['name' => $r['name']], $r);
        }

        // 2. Create Base Permissions
        $permissions = [
            ['name' => 'user.manage', 'module' => 'users', 'action' => 'manage'],
            ['name' => 'user.suspend', 'module' => 'users', 'action' => 'suspend'],
            ['name' => 'business.verify', 'module' => 'business', 'action' => 'verify'],
            ['name' => 'finance.view', 'module' => 'finance', 'action' => 'read'],
            ['name' => 'system.config', 'module' => 'system', 'action' => 'update'],
            ['name' => 'content.manage', 'module' => 'content', 'action' => 'manage'],
        ];

        foreach ($permissions as $p) {
            AdminPermission::firstOrCreate(['name' => $p['name']], $p);
        }

        // 3. Assign all to SUPER_ADMIN
        $superRole = AdminRole::where('name', 'SUPER_ADMIN')->first();
        $superRole->permissions()->sync(AdminPermission::all());

        // 4. Create Admin record for admin@piiston.com
        $user = User::where('email', 'admin@piiston.com')->first();
        if ($user) {
            $admin = Administrator::firstOrCreate(
                ['user_id' => $user->id],
                ['employee_number' => 'ADM-001', 'position' => 'Chief Technology Officer']
            );
            $admin->roles()->sync([$superRole->id]);
        }

        // 5. Initial System Settings
        SystemSetting::firstOrCreate(['setting_key' => 'maintenance_mode'], ['setting_value' => 'false', 'description' => 'Global maintenance mode toggle.']);
        SystemSetting::firstOrCreate(['setting_key' => 'platform_fee_percent'], ['setting_value' => '10', 'description' => 'Marketplace commission percentage.']);

        // 6. Initial Feature Flags
        FeatureFlag::firstOrCreate(['feature_name' => 'AI_DIAGNOSTIC'], ['enabled' => false, 'target_country' => 'ALL']);
        FeatureFlag::firstOrCreate(['feature_name' => 'MOBILE_MONEY_PAYMENT'], ['enabled' => true, 'target_country' => 'CM']);
    }
}
