<?php

namespace Database\Seeders;

use App\Models\Identity\Permission;
use App\Models\Identity\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class IdentitySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Roles
        $roles = [
            'VEHICLE_OWNER' => 'Individual vehicle owners looking for maintenance.',
            'GARAGE_OWNER' => 'Businesses providing automotive repairs.',
            'MECHANIC' => 'Professionals performing repair tasks.',
            'SPARE_PART_SELLER' => 'Businesses selling automotive parts.',
            'FLEET_MANAGER' => 'Managers of multiple professional vehicles.',
            'ADMIN' => 'System administrators.',
        ];

        foreach ($roles as $name => $desc) {
            Role::firstOrCreate(['name' => $name], ['description' => $desc]);
        }

        // 2. Create Sample Permissions
        $permissions = [
            ['name' => 'vehicle.create', 'module' => 'vehicle', 'action' => 'create'],
            ['name' => 'vehicle.view', 'module' => 'vehicle', 'action' => 'view'],
            ['name' => 'repair.manage', 'module' => 'repair', 'action' => 'manage'],
            ['name' => 'order.manage', 'module' => 'order', 'action' => 'manage'],
            ['name' => 'user.verify', 'module' => 'admin', 'action' => 'verify'],
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p['name']], $p);
        }

        // 3. Create Test Users
        $testUsers = [
            [
                'email' => 'client@piiston.com',
                'first_name' => 'John',
                'last_name' => 'Client',
                'roles' => ['VEHICLE_OWNER'],
            ],
            [
                'email' => 'mechanic@piiston.com',
                'first_name' => 'Dave',
                'last_name' => 'Mechanic',
                'roles' => ['MECHANIC'],
            ],
            [
                'email' => 'seller@piiston.com',
                'first_name' => 'Sarah',
                'last_name' => 'Seller',
                'roles' => ['SPARE_PART_SELLER'],
            ],
            [
                'email' => 'eric@piiston.com',
                'first_name' => 'Eric',
                'last_name' => 'Piiston',
                'roles' => ['VEHICLE_OWNER', 'GARAGE_OWNER'],
            ],
            [
                'email' => 'admin@piiston.com',
                'first_name' => 'System',
                'last_name' => 'Admin',
                'roles' => ['ADMIN'],
            ],
        ];

        foreach ($testUsers as $u) {
            $user = User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'first_name' => $u['first_name'],
                    'last_name' => $u['last_name'],
                    'password' => Hash::make('password'),
                    'status' => 'active',
                ]
            );
            $user->roles()->sync(Role::whereIn('name', $u['roles'])->pluck('id'));
        }

        // 4. Assign all permissions to ADMIN
        $adminRole = Role::where('name', 'ADMIN')->first();
        if ($adminRole) {
            $adminRole->permissions()->sync(Permission::all());
        }
    }
}
