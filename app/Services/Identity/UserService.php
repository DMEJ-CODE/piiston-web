<?php

namespace App\Services\Identity;

use App\Models\Identity\Role;
use App\Models\User;
use App\Repositories\Identity\UserRepositoryInterface;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function updateProfile(User $user, array $data): bool
    {
        if (isset($data['role'])) {
            $role = Role::where('name', $data['role'])->first();
            if ($role) {
                $user->roles()->sync([$role->id => ['assigned_at' => now(), 'status' => 'active']]);
            }
        }

        return $this->userRepository->updateProfile($user->id, $data);
    }

    public function managePreferences(User $user, array $preferences): void
    {
        $user->preference()->updateOrCreate(['user_id' => $user->id], $preferences);
    }

    public function deleteAccount(User $user): void
    {
        // Add any logic to cleanup related data if not cascading
        $user->delete();
    }
}
