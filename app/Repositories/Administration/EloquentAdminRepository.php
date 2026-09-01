<?php

namespace App\Repositories\Administration;

use App\Models\Administration\Administrator;
use Illuminate\Support\Collection;

class EloquentAdminRepository implements AdminRepositoryInterface
{
    public function findById(int $id): ?Administrator
    {
        return Administrator::with(['user', 'roles'])->find($id);
    }

    public function findByUserId(int $userId): ?Administrator
    {
        return Administrator::where('user_id', $userId)->first();
    }

    public function create(array $data): Administrator
    {
        return Administrator::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $admin = Administrator::find($id);
        if (! $admin) {
            return false;
        }

        return $admin->update($data);
    }

    public function all(): Collection
    {
        return Administrator::with('user')->get();
    }
}
