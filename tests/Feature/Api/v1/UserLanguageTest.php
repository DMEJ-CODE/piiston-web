<?php

namespace Tests\Feature\Api\v1;

use App\Models\Globalization\Country;
use App\Models\Globalization\Language;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserLanguageTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $country = Country::create([
            'name' => 'Cameroon',
            'iso_code' => 'CM',
            'phone_code' => '+237',
            'status' => 'active',
        ]);

        Language::create(['name' => 'English', 'code' => 'en', 'status' => 1]);
        Language::create(['name' => 'French', 'code' => 'fr', 'status' => 1]);
        Language::create(['name' => 'Retired', 'code' => 'zz', 'status' => 0]);

        $this->user = User::create([
            'first_name' => 'Ada',
            'last_name' => 'Lovelace',
            'email' => 'ada@example.com',
            'phone' => '123456789',
            'password' => bcrypt('password'),
            'country_id' => $country->id,
            'status' => 'active',
        ]);
    }

    public function test_profile_exposes_the_preferred_language()
    {
        $this->user->update(['language_id' => Language::where('code', 'fr')->value('id')]);

        $this->actingAs($this->user)
            ->getJson('/api/user/profile')
            ->assertOk()
            ->assertJsonPath('data.language.code', 'fr')
            ->assertJsonPath('data.language.name', 'French');
    }

    public function test_profile_falls_back_to_english_when_no_language_is_set()
    {
        $this->actingAs($this->user)
            ->getJson('/api/user/profile')
            ->assertOk()
            ->assertJsonPath('data.language.code', 'en');
    }

    public function test_user_can_update_their_language()
    {
        $this->actingAs($this->user)
            ->putJson('/api/user/language', ['language' => 'fr'])
            ->assertOk()
            ->assertJsonPath('user.language.code', 'fr');

        $this->assertSame(
            Language::where('code', 'fr')->value('id'),
            $this->user->fresh()->language_id,
            'The language code should be stored as a language_id foreign key.',
        );
    }

    public function test_update_language_rejects_an_unknown_code()
    {
        $this->actingAs($this->user)
            ->putJson('/api/user/language', ['language' => 'xx'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('language');

        $this->assertNull($this->user->fresh()->language_id);
    }

    public function test_update_language_rejects_an_inactive_language()
    {
        $this->actingAs($this->user)
            ->putJson('/api/user/language', ['language' => 'zz'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('language');
    }

    public function test_update_language_requires_authentication()
    {
        $this->putJson('/api/user/language', ['language' => 'fr'])->assertUnauthorized();
    }

    public function test_profile_update_maps_a_language_code_to_the_foreign_key()
    {
        $this->actingAs($this->user)
            ->patchJson('/api/user/profile', [
                'first_name' => 'Grace',
                'language' => 'fr',
            ])
            ->assertOk();

        $fresh = $this->user->fresh();

        $this->assertSame('Grace', $fresh->first_name);
        $this->assertSame(
            Language::where('code', 'fr')->value('id'),
            $fresh->language_id,
        );
    }

    public function test_profile_update_accepts_the_patch_verb_used_by_the_mobile_client()
    {
        $this->actingAs($this->user)
            ->patchJson('/api/user/profile', ['last_name' => 'Hopper'])
            ->assertOk()
            ->assertJsonPath('user.last_name', 'Hopper');
    }

    public function test_profile_update_does_not_persist_an_unknown_language()
    {
        $this->actingAs($this->user)
            ->patchJson('/api/user/profile', [
                'first_name' => 'ShouldNotStick',
                'language' => 'xx',
            ])
            ->assertStatus(422);

        $this->assertSame('Ada', $this->user->fresh()->first_name);
        $this->assertNull($this->user->fresh()->language_id);
    }
}
