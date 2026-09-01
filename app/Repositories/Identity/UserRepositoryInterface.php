<?php

namespace App\Repositories\Identity;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;

    public function findByEmail(string $email): ?User;

    public function findByPhone(string $phone): ?User;

    public function create(array $data): User;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function all(): Collection;

    public function updateProfile(int $userId, array $data): bool;

    public function syncRoles(User $user, array $roles): void;
}
