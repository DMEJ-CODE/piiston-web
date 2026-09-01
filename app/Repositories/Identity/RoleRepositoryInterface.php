<?php

namespace App\Repositories\Identity;

use App\Models\Identity\Role;
use Illuminate\Support\Collection;

interface RoleRepositoryInterface
{
    public function findByName(string $name): ?Role;

    public function all(): Collection;

    public function findById(int $id): ?Role;
}
