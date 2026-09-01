<?php

namespace App\Livewire\Auth;

use App\Models\Identity\Role;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Onboarding extends Component
{
    public $step = 1;

    // Step 1: Phone
    public $phone = '';

    public $country_code = '+237';

    public $country_flag = '🇨🇲';

    // Step 2: OTP
    public $otp = '';

    // Step 3: Role
    public $selectedRole = '';

    public $countries = [
        ['name' => 'Cameroon', 'code' => '+237', 'flag' => '🇨🇲'],
        ['name' => 'Ivory Coast', 'code' => '+225', 'flag' => '🇨🇮'],
        ['name' => 'Senegal', 'code' => '+221', 'flag' => '🇸🇳'],
        ['name' => 'Nigeria', 'code' => '+234', 'flag' => '🇳🇬'],
        ['name' => 'Kenya', 'code' => '+254', 'flag' => '🇰🇪'],
    ];

    public $roles = [
        ['id' => 'GARAGE_OWNER', 'title' => 'Garage Owner', 'desc' => 'Manage a professional workshop and mechanics.', 'icon' => 'wrench-screwdriver'],
        ['id' => 'FLEET_MANAGER', 'title' => 'Fleet Manager', 'desc' => 'Track assets, fuel and professional drivers.', 'icon' => 'truck'],
    ];

    public function mount()
    {
        $user = Auth::user();

        if ($user && ($user->hasRole('ADMIN') || $user->administrator()->exists())) {
            $this->redirect(route('dashboard'));
        }
    }

    public function setCountry($code, $flag)
    {
        $this->country_code = $code;
        $this->country_flag = $flag;
    }

    public function sendOtp()
    {
        $this->validate(['phone' => 'required|min:8']);
        // Simulate sending
        $this->step = 2;
    }

    public function updatedOtp($value)
    {
        if (strlen($value) === 6) {
            $this->verifyOtp();
        }
    }

    public function verifyOtp()
    {
        $this->validate(['otp' => 'required|size:6']);

        if ($this->otp === '123456') {
            $user = Auth::user();
            $user->update([
                'phone' => $this->country_code.$this->phone,
                'phone_verified_at' => now(),
            ]);
            $this->step = 3;
        } else {
            $this->addError('otp', 'Invalid verification code.');
        }
    }

    public function selectRole($roleId)
    {
        $this->selectedRole = $roleId;
    }

    public function finish()
    {
        if (! $this->selectedRole) {
            return;
        }

        $user = Auth::user();
        $role = Role::where('name', $this->selectedRole)->first();

        if ($role) {
            $user->roles()->sync([$role->id => ['assigned_at' => now(), 'status' => 'active']]);
        }

        // Auto-creation logic removed. Handled by /garage/setup after onboarding.
        return redirect()->intended(route('dashboard'));
    }

    #[Layout('layouts.auth.card')]
    public function render()
    {
        return view('livewire.auth.onboarding');
    }
}
