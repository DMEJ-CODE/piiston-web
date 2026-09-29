<?php

namespace App\Services\Identity;

use App\Models\Identity\Role;
use App\Models\User;
use App\Repositories\Identity\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data): array
    {
        $data['password'] = Hash::make($data['password']);

        // Mark as verified since they completed the OTP flow
        $data['phone_verified_at'] = now();
        $data['email_verified_at'] = now(); // Auto-verify for development

        $user = $this->userRepository->create($data);

        // Assign the role selected by the user
        if (isset($data['role'])) {
            \Log::info('Searching for role: '.$data['role']);
            $role = Role::whereRaw('UPPER(name) = ?', [strtoupper($data['role'])])->first();
            if ($role) {
                \Log::info('Found role. ID: '.$role->id);
                $user->roles()->attach($role->id, ['assigned_at' => now(), 'status' => 'active']);

                // Create profile based on role
                if (strtoupper($data['role']) === 'SPARE_PART_SELLER') {
                    $user->sellerProfile()->create([
                        'country_id' => $user->country_id ?? 1, // Default to 1 if not set
                        'business_name' => $user->getNameAttribute()."'s Store",
                        'business_type' => 'PART_STORE',
                        'status' => true,
                    ]);
                } elseif (strtoupper($data['role']) === 'MECHANIC') {
                    $user->mechanicProfile()->create([
                        'country_id' => $user->country_id ?? 1,
                        'years_of_experience' => 0,
                        'availability_status' => 'available',
                    ]);
                }
            } else {
                \Log::warning('Role NOT found: '.$data['role']);
            }
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user->load('roles'),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];
    }

    public function login(array $credentials): array
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    public function logoutFromAllDevices(User $user): void
    {
        $user->tokens()->delete();
    }

    public function forgotPassword(string $email): string
    {
        // In a real production app, use Password::broker()->sendResetLink()
        // For now, we simulate success if user exists
        $user = $this->userRepository->findByEmail($email);
        if (! $user) {
            throw ValidationException::withMessages(['email' => ['User not found.']]);
        }

        return 'Password reset link sent to your email.';
    }

    public function sendOtp(string $phone): string
    {
        // For development, we just simulate sending.
        // In production, integrate with SMS gateway (Twilio, Orange SMS, etc.)
        return "OTP sent to $phone. Use 123456 for testing.";
    }

    public function verifyOtp(?User $user, string $code, string $phone): bool
    {
        // Simple verification for development
        if ($code === '123456') {
            if ($user) {
                $user->update([
                    'phone' => $phone,
                    'phone_verified_at' => now(),
                ]);
            }

            return true;
        }

        return false;
    }
}
