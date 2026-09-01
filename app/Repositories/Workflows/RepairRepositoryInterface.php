<?php

namespace App\Repositories\Workflows;

use App\Models\Garages\RepairOrder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface RepairRepositoryInterface
{
    public function findById(int $id): ?RepairOrder;

    public function getUserRepairs(int $userId): Collection;

    public function getGarageRepairs(int $branchId): LengthAwarePaginator;

    public function search(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function create(array $data): RepairOrder;

    public function update(int $id, array $data): bool;

    public function getHistory(int $repairId): Collection;
}
