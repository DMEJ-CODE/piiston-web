<?php

namespace Tests\Feature\Livewire\Admin;

use App\Models\Administration\Administrator;
use App\Models\Administration\AdminPermission;
use App\Models\Administration\AdminRole;
use App\Models\Globalization\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class AdminTestCase extends TestCase
{
    use RefreshDatabase;

    protected function createAdminUser(): User
    {
        $country = Country::create([
            'name' => 'Cameroon',
            'iso_code' => 'CM',
            'phone_code' => '+237',
            'status' => 'active',
        ]);

        $user = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@test.com',
            'phone' => '123456789',
            'password' => bcrypt('password'),
            'country_id' => $country->id,
            'status' => 'active',
        ]);

        $adminRole = AdminRole::create([
            'name' => 'TEST_ADMIN',
            'description' => 'Test admin role',
            'level' => 10,
            'status' => true,
        ]);

        $permission = AdminPermission::create([
            'name' => 'test.manage',
            'module' => 'test',
            'action' => 'manage',
            'description' => 'Test permission',
            'status' => true,
        ]);

        $adminRole->permissions()->attach($permission->id);

        $admin = Administrator::create([
            'user_id' => $user->id,
            'employee_number' => 'ADM-TEST',
            'position' => 'Tester',
            'status' => true,
        ]);

        $admin->roles()->attach($adminRole->id);

        return $user;
    }

    protected function actingAsAdmin(): User
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        return $admin;
    }
}
