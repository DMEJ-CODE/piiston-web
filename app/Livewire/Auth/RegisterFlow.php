<?php

namespace App\Livewire\Auth;

use App\Models\Globalization\Country;
use App\Models\Identity\Role;
use App\Models\User;
use App\Services\Identity\AuthService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RegisterFlow extends Component
{
    public $step = 1;

    // Step 1: Info
    public $name = '';

    public $email = '';

    public $password = '';

    public $password_confirmation = '';

    // Step 2: Phone
    public $phone = '';

    public $country_code = '+237';

    public $country_flag = '🇨🇲';

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

    public function nextStep()
    {
        if ($this->step === 1) {
            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);
            $this->step = 2;
        }
    }

    public function setCountry($code, $flag)
    {
        $this->country_code = $code;
        $this->country_flag = $flag;
    }

    public function sendOtp()
    {
        $fullPhone = $this->country_code.$this->phone;

        $this->validate([
            'phone' => 'required|min:8',
        ]);

        if (User::where('phone', $fullPhone)->exists()) {
            $this->addError('phone', 'This phone number is already registered.');

            return;
        }

        // Simulate sending
        $this->step = 3;
    }

    public function updatedPhone()
    {
        // Optional: clear OTP if phone changes
        $this->otp = '';
    }

    public function updatedOtp($value)
    {
        if (strlen($value) === 6) {
            $this->verifyOtp();
        }
    }

    public function verifyOtp()
    {
        $this->validate(['phone' => 'required|min:8', 'otp' => 'required|size:6']);

        if ($this->otp === '123456') {
            $this->step = 4;
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

        if (! Role::where('name', $this->selectedRole)->exists()) {
            $this->addError('selectedRole', 'The selected role is not available. Please contact support.');

            return;
        }

        $authService = app(AuthService::class);

        $country = Country::where('phone_code', $this->country_code)->first();

        $result = $authService->register([
            'first_name' => explode(' ', $this->name)[0],
            'last_name' => explode(' ', $this->name)[1] ?? '',
            'email' => $this->email,
            'phone' => $this->country_code.$this->phone,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'role' => $this->selectedRole,
            'country_id' => $country?->id,
            'status' => 'active',
        ]);

        Auth::login($result['user']);

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.auth.register-flow');
    }
}
