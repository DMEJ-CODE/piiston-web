<?php

namespace App\Policies;

use App\Models\Mechanics\MechanicProfile;
use App\Models\User;

class MechanicPolicy
{
    public function update(User $user, MechanicProfile $profile): bool
    {
        return $user->id === $profile->user_id || $user->hasRole('ADMIN');
    }

    public function manageEmployment(User $user, MechanicProfile $profile): bool
    {
        // For now, only Admin or the Mechanic can manage their own employment details
        // In a real garage flow, the Garage Owner might also have some rights.
        return $user->id === $profile->user_id || $user->hasRole('ADMIN');
    }
}
