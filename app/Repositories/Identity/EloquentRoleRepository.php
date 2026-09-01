<?php

namespace App\Repositories\Identity;

use App\Models\Identity\Role;
use Illuminate\Support\Collection;

class EloquentRoleRepository implements RoleRepositoryInterface
{
    public function findByName(string $name): ?Role
    {
        return Role::where('name', $name)->first();
    }

    public function all(): Collection
    {
        return Role::all();
    }

    public function findById(int $id): ?Role
    {
        return Role::find($id);
    }
}
