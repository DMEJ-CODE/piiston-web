<?php

namespace Tests\Feature;

use App\Livewire\LanguageSwitcher;
use App\Models\Globalization\Language;
use App\Models\Identity\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LanguageSwitcherTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::firstOrCreate(
            ['name' => 'VEHICLE_OWNER'],
            ['description' => 'Individual vehicle owners']
        );

        Language::firstOrCreate(['code' => 'en'], ['name' => 'English', 'status' => true]);
        Language::firstOrCreate(['code' => 'fr'], ['name' => 'French', 'status' => true]);

        $this->user = User::factory()->create([
            'phone_verified_at' => now(),
        ]);
        $this->user->roles()->sync([$role->id]);
    }

    public function test_language_switcher_component_can_be_rendered(): void
    {
        $this->actingAs($this->user);

        $component = Livewire::test(LanguageSwitcher::class);

        $component->assertSee('English');
        $component->assertSee('French');
    }

    public function test_user_can_switch_to_french(): void
    {
        $this->actingAs($this->user);

        $fr = Language::where('code', 'fr')->firstOrFail();

        Livewire::test(LanguageSwitcher::class)
            ->call('switchLocale', 'fr')
            ->assertSet('currentLocale', 'fr');

        $this->user->refresh();
        $this->assertEquals($fr->id, $this->user->preference->language_id);
    }

    public function test_user_can_switch_to_english(): void
    {
        $this->actingAs($this->user);

        $en = Language::where('code', 'en')->firstOrFail();

        Livewire::test(LanguageSwitcher::class)
            ->call('switchLocale', 'en')
            ->assertSet('currentLocale', 'en');

        $this->user->refresh();
        $this->assertEquals($en->id, $this->user->preference->language_id);
    }

    public function test_locale_is_persisted_in_session(): void
    {
        $this->actingAs($this->user);

        Livewire::test(LanguageSwitcher::class)
            ->call('switchLocale', 'fr');

        $this->assertEquals('fr', session('locale'));
    }
}
