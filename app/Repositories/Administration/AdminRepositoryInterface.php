<?php

namespace App\Repositories\Administration;

use App\Models\Administration\Administrator;
use Illuminate\Support\Collection;

interface AdminRepositoryInterface
{
    public function findById(int $id): ?Administrator;

    public function findByUserId(int $userId): ?Administrator;

    public function create(array $data): Administrator;

    public function update(int $id, array $data): bool;

    public function all(): Collection;
}
