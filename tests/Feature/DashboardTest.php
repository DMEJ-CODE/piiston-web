<?php

use App\Models\Identity\Role;
use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create([
        'phone_verified_at' => now(),
    ]);
    $user->roles()->attach(Role::firstOrCreate(['name' => 'VEHICLE_OWNER']));
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('admin users are redirected to the admin dashboard', function () {
    $user = User::factory()->create();
    $user->administrator()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('admin.dashboard'));
});

test('garage owner users are redirected to the garage dashboard', function () {
    $user = User::factory()->create([
        'phone_verified_at' => now(),
    ]);
    $user->roles()->attach(Role::firstOrCreate(['name' => 'GARAGE_OWNER']));
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('garage.dashboard'));
});

test('mechanic users are redirected to the garage dashboard', function () {
    $user = User::factory()->create([
        'phone_verified_at' => now(),
    ]);
    $user->roles()->attach(Role::firstOrCreate(['name' => 'MECHANIC']));
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('garage.dashboard'));
});
