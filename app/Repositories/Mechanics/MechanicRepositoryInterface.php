<?php

namespace App\Repositories\Mechanics;

use App\Models\Mechanics\MechanicProfile;
use Illuminate\Pagination\LengthAwarePaginator;

interface MechanicRepositoryInterface
{
    public function findById(int $id): ?MechanicProfile;

    public function findByUserId(int $userId): ?MechanicProfile;

    public function search(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function create(array $data): MechanicProfile;

    public function update(int $id, array $data): bool;
}
