<?php

namespace Tests\Feature\Api\v1;

use App\Models\Globalization\Country;
use App\Models\Mechanics\MechanicProfile;
use App\Models\Mechanics\MechanicType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MechanicTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $country;

    protected $type;

    protected function setUp(): void
    {
        parent::setUp();

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
    }

    public function test_user_can_create_mechanic_profile()
    {
        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/mechanics', [
                'professional_title' => 'Senior Engine Technician',
                'years_of_experience' => 10,
                'bio' => 'Expert in German engines.',
                'type_id' => $this->type->id,
                'country_id' => $this->country->id,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('profile.professional_title', 'Senior Engine Technician');

        $this->assertDatabaseHas('mechanic_profiles', ['user_id' => $this->user->id]);
    }

    public function test_public_can_list_mechanics()
    {
        MechanicProfile::create([
            'user_id' => $this->user->id,
            'country_id' => $this->country->id,
            'type_id' => $this->type->id,
            'professional_title' => 'Master Mechanic',
            'years_of_experience' => 15,
            'verification_status' => 'verified',
        ]);

        $response = $this->getJson('/api/mechanics');

        $response->assertStatus(200)
            ->assertJsonFragment(['professional_title' => 'Master Mechanic']);
    }
}
