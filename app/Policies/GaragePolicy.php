<?php

namespace App\Policies;

use App\Models\Garages\GarageBranch;
use App\Models\Garages\GarageCompany;
use App\Models\User;

class GaragePolicy
{
    public function manage(User $user, GarageCompany $garage): bool
    {
        return $user->id === $garage->owner_id || $user->hasRole('ADMIN');
    }

    public function manageBranch(User $user, GarageBranch $branch): bool
    {
        if ($user->hasRole('ADMIN')) {
            return true;
        }

        // Owner can always manage the branch structure/settings
        if ($user->id === $branch->company->owner_id) {
            return true;
        }

        // Assigned manager
        if ($user->id === $branch->manager_id) {
            return true;
        }

        return $branch->employees()->where('user_id', $user->id)->exists();
    }

    public function operate(User $user, GarageBranch $branch): bool
    {
        if ($user->hasRole('ADMIN')) {
            return true;
        }

        // Logic: Grand Owner can only operate IF there is NO assigned manager, or IF he IS the assigned manager.
        // If he assigned it to someone else, he can only "View/Manage General" but not "Operate" (add repairs etc)
        if ($user->id === $branch->company->owner_id) {
            return is_null($branch->manager_id) || $branch->manager_id === $user->id;
        }

        // Assigned manager can always operate
        if ($user->id === $branch->manager_id) {
            return true;
        }

        // Authorized employees
        return $branch->employees()->where('user_id', $user->id)->exists();
    }

    public function view(User $user, $model): bool
    {
        if ($user->hasRole('ADMIN')) {
            return true;
        }

        if ($model instanceof GarageCompany) {
            return $user->id === $model->owner_id;
        }

        if (property_exists($model, 'company') && $model->company) {
            return $user->id === $model->company->owner_id;
        }

        if (property_exists($model, 'branch') && $model->branch) {
            return $user->id === $model->branch->company->owner_id
                || $user->id === $model->branch->manager_id;
        }

        return false;
    }

    public function create(User $user, $model): bool
    {
        if ($user->hasRole('ADMIN')) {
            return true;
        }

        if (property_exists($model, 'branch_id')) {
            $branch = GarageBranch::find($model->branch_id);
            if ($branch) {
                return $user->id === $branch->company->owner_id
                    || $user->id === $branch->manager_id;
            }
        }

        return false;
    }

    public function update(User $user, $model): bool
    {
        return $this->view($user, $model);
    }

    public function delete(User $user, $model): bool
    {
        return $this->view($user, $model);
    }
}
