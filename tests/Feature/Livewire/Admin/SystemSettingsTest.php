<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\SystemSettings;
use App\Models\Administration\SystemSetting;

class SystemSettingsTest extends AdminTestCase
{
    public function test_can_save_system_settings()
    {
        $this->actingAsAdmin();

        $component = new SystemSettings;
        $component->form = [
            'maintenance_mode' => '1',
            'platform_name' => 'Test Platform',
        ];

        $component->saveSettings();

        $this->assertDatabaseHas('system_settings', [
            'setting_key' => 'maintenance_mode',
            'setting_value' => '1',
        ]);

        $this->assertDatabaseHas('system_settings', [
            'setting_key' => 'platform_name',
            'setting_value' => 'Test Platform',
        ]);
    }

    public function test_system_settings_form_loads_existing_values()
    {
        $this->actingAsAdmin();

        SystemSetting::create([
            'setting_key' => 'maintenance_mode',
            'setting_value' => 'true',
            'description' => 'Maintenance mode',
        ]);

        $component = new SystemSettings;
        $component->mount();

        $this->assertTrue(isset($component->form['maintenance_mode']));
    }
}
