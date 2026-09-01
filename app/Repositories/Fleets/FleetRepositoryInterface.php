<?php

namespace App\Repositories\Fleets;

use App\Models\Fleets\Fleet;
use Illuminate\Support\Collection;

interface FleetRepositoryInterface
{
    public function findById(int $id): ?Fleet;

    public function getCompanyFleets(int $companyId): Collection;

    public function create(array $data): Fleet;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function getFleetVehicles(int $fleetId): Collection;

    public function getFleetDrivers(int $fleetId): Collection;

    public function getFleetExpenses(int $fleetId): Collection;
}
