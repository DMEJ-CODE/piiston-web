<?php

use App\Models\Garages\GarageBranch;
use App\Models\Garages\GarageCompany;
use App\Models\Garages\GarageInvitation;
use App\Models\Globalization\Country;
use App\Models\Mechanics\MechanicProfile;
use App\Models\Mechanics\MechanicType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->country = Country::create([
        'name' => 'Cameroon',
        'iso_code' => 'CM',
        'phone_code' => '+237',
        'status' => 'active',
    ]);

    $this->user = User::create([
        'first_name' => 'Dave',
        'last_name' => 'Mechanic',
        'email' => 'dave@mechanic.com',
        'phone' => '123456789',
        'password' => bcrypt('password'),
        'country_id' => $this->country->id,
        'status' => 'active',
    ]);

    $this->type = MechanicType::create(['name' => 'Mobile Mechanic']);

    $this->profile = MechanicProfile::create([
        'user_id' => $this->user->id,
        'country_id' => $this->country->id,
        'type_id' => $this->type->id,
        'professional_title' => 'Initial Title',
        'years_of_experience' => 5,
        'verification_status' => 'pending',
    ]);
});

test('mechanic can update their profile', function () {
    Sanctum::actingAs($this->user);

    $response = $this->putJson('/api/mechanic/profile', [
        'professional_title' => 'Updated Expert Title',
        'years_of_experience' => 10,
        'availability_status' => 'AVAILABLE',
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('profile.professional_title', 'Updated Expert Title');

    $this->assertDatabaseHas('mechanic_profiles', [
        'id' => $this->profile->id,
        'professional_title' => 'Updated Expert Title',
        'years_of_experience' => 10,
    ]);
});

test('mechanic can list their invitations', function () {
    Sanctum::actingAs($this->user);

    $company = GarageCompany::create([
        'name' => 'Test Garage',
        'owner_id' => $this->user->id,
        'country_id' => $this->country->id,
    ]);

    $branch = GarageBranch::create([
        'company_id' => $company->id,
        'name' => 'Branch 1',
        'city' => 'Douala',
    ]);

    GarageInvitation::create([
        'branch_id' => $branch->id,
        'email' => $this->user->email,
        'role' => 'MECHANIC',
        'token' => 'test-token',
        'expires_at' => now()->addDays(7),
    ]);

    $response = $this->getJson('/api/mechanic/invitations');

    $response->assertStatus(200)
        ->assertJsonCount(1);
});

test('mechanic can accept a garage invitation', function () {
    Sanctum::actingAs($this->user);

    $otherUser = User::create([
        'first_name' => 'Owner',
        'last_name' => 'Garage',
        'email' => 'owner@garage.com',
        'phone' => '987654321',
        'password' => bcrypt('password'),
        'country_id' => $this->country->id,
    ]);

    $company = GarageCompany::create([
        'name' => 'Test Garage',
        'owner_id' => $otherUser->id,
        'country_id' => $this->country->id,
    ]);

    $branch = GarageBranch::create([
        'company_id' => $company->id,
        'name' => 'Branch 1',
    ]);

    $invitation = GarageInvitation::create([
        'branch_id' => $branch->id,
        'email' => $this->user->email,
        'role' => 'SENIOR_MECHANIC',
        'token' => 'test-token',
        'expires_at' => now()->addDays(7),
    ]);

    $response = $this->postJson("/api/mechanic/invitations/{$invitation->id}/accept");

    $response->assertStatus(200);

    $this->assertDatabaseHas('garage_invitations', [
        'id' => $invitation->id,
    ]);

    $this->assertDatabaseHas('garage_employees', [
        'branch_id' => $branch->id,
        'user_id' => $this->user->id,
        'position' => 'MECHANIC',
    ]);

    $this->assertDatabaseHas('mechanic_employments', [
        'mechanic_id' => $this->profile->id,
        'branch_id' => $branch->id,
    ]);
});
