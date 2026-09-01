<?php

namespace App\Http\Controllers\Api\v1\Fleet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fleets\FleetRequest;
use App\Http\Resources\Fleets\FleetResource;
use App\Repositories\Fleets\FleetRepositoryInterface;
use App\Services\Fleets\FleetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FleetController extends Controller
{
    protected $fleetService;

    protected $fleetRepository;

    public function __construct(FleetService $fleetService, FleetRepositoryInterface $fleetRepository)
    {
        $this->fleetService = $fleetService;
        $this->fleetRepository = $fleetRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $companyId = $request->query('company_id');
        if (! $companyId) {
            return response()->json(['message' => 'company_id required'], 400);
        }

        $fleets = $this->fleetRepository->getCompanyFleets($companyId);

        return response()->json(FleetResource::collection($fleets));
    }

    public function store(FleetRequest $request): JsonResponse
    {
        $fleet = $this->fleetService->createFleet($request->validated());

        return response()->json([
            'message' => 'Fleet created successfully',
            'fleet' => new FleetResource($fleet),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $fleet = $this->fleetRepository->findById($id);
        if (! $fleet) {
            return response()->json(['message' => 'Fleet not found'], 404);
        }

        return response()->json(new FleetResource($fleet->load('manager')));
    }
}
