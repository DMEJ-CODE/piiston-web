<?php

namespace App\Http\Controllers\Api\v1\Fleet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fleets\DriverAssignmentRequest;
use App\Repositories\Fleets\FleetRepositoryInterface;
use App\Services\Fleets\FleetService;
use Illuminate\Http\JsonResponse;

class DriverController extends Controller
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
        $drivers = $this->fleetRepository->getFleetDrivers($fleetId);

        return response()->json($drivers);
    }

    public function store(DriverAssignmentRequest $request, int $fleetId): JsonResponse
    {
        $member = $this->fleetService->assignMember($fleetId, $request->user_id, $request->role);

        return response()->json([
            'message' => 'Member assigned to fleet successfully',
            'member' => $member,
        ], 201);
    }
}
