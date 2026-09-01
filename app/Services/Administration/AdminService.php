<?php

namespace App\Services\Administration;

use App\Models\Administration\Administrator;
use App\Models\User;
use App\Repositories\Administration\AdminRepositoryInterface;

class AdminService
{
    protected $adminRepository;

    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function banUser(User $user, string $reason): void
    {
        $user->update(['status' => 'banned']);
        // Future: trigger UserBanned event
    }

    public function restoreUser(User $user): void
    {
        $user->update(['status' => 'active']);
    }

    public function registerAdmin(User $user, string $position): Administrator
    {
        return $this->adminRepository->create([
            'user_id' => $user->id,
            'position' => $position,
            'status' => 'active',
            'employee_number' => 'ADM-'.time(),
        ]);
    }
}
