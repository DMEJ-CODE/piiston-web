<?php

namespace App\Repositories\Fleets;

use App\Models\Fleets\Fleet;
use App\Models\Fleets\FleetMember;
use App\Models\Fleets\FleetVehicle;
use Illuminate\Support\Collection;

class EloquentFleetRepository implements FleetRepositoryInterface
{
    public function findById(int $id): ?Fleet
    {
        return Fleet::with(['company', 'manager'])->find($id);
    }

    public function getCompanyFleets(int $companyId): Collection
    {
        return Fleet::where('company_id', $companyId)->get();
    }

    public function create(array $data): Fleet
    {
        return Fleet::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $fleet = Fleet::find($id);
        if (! $fleet) {
            return false;
        }

        return $fleet->update($data);
    }

    public function delete(int $id): bool
    {
        $fleet = Fleet::find($id);
        if (! $fleet) {
            return false;
        }

        return $fleet->delete();
    }

    public function getFleetVehicles(int $fleetId): Collection
    {
        return FleetVehicle::where('fleet_id', $fleetId)
            ->with(['vehicle.brand', 'vehicle.model'])
            ->get();
    }

    public function getFleetDrivers(int $fleetId): Collection
    {
        return FleetMember::where('fleet_id', $fleetId)
            ->where('role', 'DRIVER')
            ->with('user')
            ->get();
    }

    public function getFleetExpenses(int $fleetId): Collection
    {
        $fleet = Fleet::find($fleetId);
        if (! $fleet) {
            return collect();
        }

        return $fleet->expenses()->with(['vehicle', 'currency'])->get();
    }
}
