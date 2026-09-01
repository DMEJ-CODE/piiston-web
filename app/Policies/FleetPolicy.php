<?php

namespace App\Policies;

use App\Models\Fleets\Fleet;
use App\Models\User;

class FleetPolicy
{
    public function manage(User $user, Fleet $fleet): bool
    {
        return $user->id === $fleet->manager_id
            || $user->id === $fleet->company->owner_id
            || $user->hasRole('ADMIN');
    }

    public function view(User $user, Fleet $fleet): bool
    {
        return $this->manage($user, $fleet)
            || $fleet->members()->where('user_id', $user->id)->exists()
            || $user->hasRole('ADMIN');
    }
}
