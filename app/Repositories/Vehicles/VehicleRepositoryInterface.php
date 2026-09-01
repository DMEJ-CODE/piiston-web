<?php

namespace App\Repositories\Vehicles;

use App\Models\Vehicles\Vehicle;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface VehicleRepositoryInterface
{
    public function findById(int $id): ?Vehicle;

    public function findByVin(string $vin): ?Vehicle;

    public function create(array $data): Vehicle;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function getUserVehicles(int $userId): Collection;

    public function search(array $filters, int $perPage = 15): LengthAwarePaginator;
}
