<?php

namespace App\Services\Fleets;

use App\Models\Fleets\Fleet;
use App\Models\Fleets\FleetMember;
use App\Models\Fleets\FleetVehicle;
use App\Repositories\Fleets\FleetRepositoryInterface;

class FleetService
{
    protected $fleetRepository;

    public function __construct(FleetRepositoryInterface $fleetRepository)
    {
        $this->fleetRepository = $fleetRepository;
    }

    public function createFleet(array $data): Fleet
    {
        return $this->fleetRepository->create($data);
    }

    public function assignVehicle(int $fleetId, int $vehicleId): FleetVehicle
    {
        return FleetVehicle::create([
            'fleet_id' => $fleetId,
            'vehicle_id' => $vehicleId,
            'assigned_date' => now(),
            'status' => 'active',
        ]);
    }

    public function assignMember(int $fleetId, int $userId, string $role): FleetMember
    {
        return FleetMember::create([
            'fleet_id' => $fleetId,
            'user_id' => $userId,
            'role' => $role,
            'status' => 'active',
        ]);
    }

    public function recordExpense(int $fleetId, array $data)
    {
        $fleet = $this->fleetRepository->findById($fleetId);

        return $fleet->expenses()->create($data);
    }
}
