<?php

namespace App\Policies;

use App\Models\Garages\RepairOrder;
use App\Models\User;

class RepairOrderPolicy
{
    public function view(User $user, RepairOrder $repair): bool
    {
        if ($user->hasRole('ADMIN')) {
            return true;
        }

        // Customer who owns the vehicle
        if ($repair->garageCustomer && $user->id === $repair->garageCustomer->user_id) {
            return true;
        }

        // Branch owner, manager or employees
        if ($repair->branch) {
            if ($repair->branch->company && $user->id === $repair->branch->company->owner_id) {
                return true;
            }

            if ($user->id === $repair->branch->manager_id) {
                return true;
            }

            // Check if user is an employee of this branch
            return $repair->branch->employees()->where('user_id', $user->id)->exists();
        }

        return false;
    }

    public function update(User $user, RepairOrder $repair): bool
    {
        if ($user->hasRole('ADMIN')) {
            return true;
        }

        $employee = $repair->branch->employees()->where('user_id', $user->id)->first();

        // Branch owner, manager
        if ($repair->branch) {
            if (($repair->branch->company && $user->id === $repair->branch->company->owner_id) ||
                ($user->id === $repair->branch->manager_id)) {
                return true;
            }
        }

        if ($employee) {
            // Mechanics can only update if assigned
            if (in_array($employee->position, ['Mechanic', 'Diagnostic Technician'])) {
                return $user->id === $repair->assigned_mechanic_id;
            }

            // Workshop Managers and Garage Managers can update everything
            if (in_array($employee->position, ['Garage Manager', 'Workshop Manager'])) {
                return true;
            }
        }

        return false;
    }

    public function qualityCheck(User $user, RepairOrder $repair): bool
    {
        $employee = $repair->branch->employees()->where('user_id', $user->id)->first();

        return $employee && in_array($employee->position, ['Garage Manager', 'Workshop Manager', 'Quality Controller']);
    }

    public function approve(User $user, RepairOrder $repair): bool
    {
        // For approval, it must be the user who owns the vehicle/customer record
        return ($repair->garageCustomer && $user->id === $repair->garageCustomer->user_id)
            || $user->hasRole('ADMIN');
    }

    public function delete(User $user, RepairOrder $repair): bool
    {
        return $user->hasRole('ADMIN');
    }
}
