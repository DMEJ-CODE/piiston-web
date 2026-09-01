<?php

namespace App\Repositories\Identity;

use App\Models\User;
use Illuminate\Support\Collection;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findByPhone(string $phone): ?User
    {
        return User::where('phone', $phone)->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $user = $this->findById($id);
        if (! $user) {
            return false;
        }

        return $user->update($data);
    }

    public function delete(int $id): bool
    {
        $user = $this->findById($id);
        if (! $user) {
            return false;
        }

        return $user->delete();
    }

    public function all(): Collection
    {
        return User::all();
    }

    public function updateProfile(int $userId, array $data): bool
    {
        return $this->update($userId, $data);
    }

    public function syncRoles(User $user, array $roles): void
    {
        $user->roles()->sync($roles);
    }
}
