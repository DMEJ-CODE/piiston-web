<?php

namespace App\Livewire\Admin;

use App\Models\Administration\SystemSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

class SystemSettings extends Component
{
    public $settings = [];

    public $form = [];

    public function mount()
    {
        $this->settings = SystemSetting::all()->keyBy('setting_key')->toArray();
        $this->form = collect($this->settings)
            ->mapWithKeys(fn ($setting) => [$setting['setting_key'] => $setting['setting_value']])
            ->toArray();
    }

    public function saveSettings()
    {
        foreach ($this->form as $key => $value) {
            SystemSetting::updateOrCreate(
                ['setting_key' => $key],
                ['setting_value' => $value]
            );
        }
        $this->dispatch('notify', message: 'Settings saved successfully');
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin.system-settings');
    }
}
