<?php

namespace App\Http\Controllers\Api\v1\Fleet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fleets\VehicleAssignmentRequest;
use App\Repositories\Fleets\FleetRepositoryInterface;
use App\Services\Fleets\FleetService;
use Illuminate\Http\JsonResponse;

class VehicleController extends Controller
{
    protected $fleetService;

    protected $fleetRepository;

    public function __construct(FleetService $fleetService, FleetRepositoryInterface $fleetRepository)
    {
        $this->fleetService = $fleetService;
        $this->fleetRepository = $fleetRepository;
    }

    public function index(int $fleetId): JsonResponse
    {
        $vehicles = $this->fleetRepository->getFleetVehicles($fleetId);

        return response()->json($vehicles);
    }

    public function store(VehicleAssignmentRequest $request, int $fleetId): JsonResponse
    {
        $assignment = $this->fleetService->assignVehicle($fleetId, $request->vehicle_id);

        return response()->json([
            'message' => 'Vehicle assigned to fleet successfully',
            'assignment' => $assignment,
        ], 201);
    }
}
