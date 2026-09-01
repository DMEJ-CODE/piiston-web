<?php

namespace App\Services\Garages;

use App\Models\Garages\GarageBranch;
use App\Models\Garages\GarageEmployee;
use App\Models\User;

class EmployeeService
{
    public function hireEmployee(GarageBranch $branch, User $user, array $data): GarageEmployee
    {
        return $branch->employees()->create(array_merge($data, ['user_id' => $user->id]));
    }

    public function updateEmployee(GarageEmployee $employee, array $data): bool
    {
        return $employee->update($data);
    }

    public function terminateEmployee(GarageEmployee $employee): bool
    {
        return $employee->delete();
    }

    public function getBranchEmployees(GarageBranch $branch, int $perPage = 15)
    {
        return GarageEmployee::where('branch_id', $branch->id)
            ->with(['user', 'department'])
            ->paginate($perPage);
    }

    public function getMechanics(GarageBranch $branch)
    {
        return GarageEmployee::where('branch_id', $branch->id)
            ->where('position', 'MECHANIC')
            ->with('user')
            ->get();
    }
}
