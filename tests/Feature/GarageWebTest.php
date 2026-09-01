<?php

use App\Models\Garages\GarageCompany;
use App\Models\Globalization\Country;
use App\Models\Identity\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GarageWebTest extends TestCase
{
    use RefreshDatabase;

    protected $owner;

    protected $branch;

    protected function setUp(): void
    {
        parent::setUp();

        $country = Country::create([
            'name' => 'Cameroon',
            'iso_code' => 'CM',
            'phone_code' => '+237',
            'status' => 'active',
        ]);

        $this->owner = User::create([
            'first_name' => 'Garage',
            'last_name' => 'Owner',
            'email' => 'garage-owner-web@test.com',
            'phone' => '123456789',
            'phone_verified_at' => now(),
            'password' => bcrypt('password'),
            'country_id' => $country->id,
            'status' => 'active',
        ]);

        $garageOwnerRole = Role::create(['name' => 'GARAGE_OWNER', 'status' => 'active']);
        $this->owner->roles()->attach($garageOwnerRole->id, ['assigned_at' => now(), 'status' => 'active']);

        $garage = GarageCompany::create([
            'owner_id' => $this->owner->id,
            'country_id' => $country->id,
            'name' => 'Test Garage',
            'email' => 'garage@test.com',
            'phone' => '111',
            'status' => 'active',
        ]);

        $this->branch = $garage->branches()->create([
            'name' => 'Douala Branch',
            'phone' => '222',
            'email' => 'dla@test.com',
            'status' => 'active',
        ]);
    }

    public function test_owner_can_access_garage_customers_page()
    {
        $this->owner->email_verified_at = now();
        $this->owner->save();

        $response = $this->actingAs($this->owner)->get('/garage/customers');
        $response->assertStatus(200);
        $response->assertSee('Clients');
    }

    public function test_sidebar_contains_correct_links()
    {
        $this->owner->email_verified_at = now();
        $this->owner->save();

        $response = $this->actingAs($this->owner)->get('/garage/customers');
        $response->assertStatus(200);
        $response->assertSee('href="'.route('garage.customers.index').'"', false);
        $response->assertSee('href="'.route('garage.appointments.index').'"', false);
        $response->assertSee('href="'.route('garage.repairs.index').'"', false);
    }

    public function test_owner_can_access_garage_repairs_page()
    {
        $response = $this->actingAs($this->owner)->get('/garage/repairs');
        $response->assertStatus(200);
        $response->assertSee('Réparations');
    }

    public function test_owner_can_access_garage_dashboard_page()
    {
        $response = $this->actingAs($this->owner)->get('/garage/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Tableau de bord');
    }
}
