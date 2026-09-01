<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicles\Vehicle;

class VehiclePolicy
{
    public function view(User $user, Vehicle $vehicle): bool
    {
        return $user->id === $vehicle->owner_id || $user->hasRole('ADMIN');
    }

    public function update(User $user, Vehicle $vehicle): bool
    {
        return $user->id === $vehicle->owner_id || $user->hasRole('ADMIN');
    }

    public function delete(User $user, Vehicle $vehicle): bool
    {
        return $user->id === $vehicle->owner_id || $user->hasRole('ADMIN');
    }
}
