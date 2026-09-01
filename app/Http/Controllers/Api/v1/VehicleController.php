<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehicles\StoreVehicleRequest;
use App\Http\Resources\Vehicles\VehicleResource;
use App\Models\Vehicles\Vehicle;
use App\Repositories\Vehicles\VehicleRepositoryInterface;
use App\Services\Vehicles\VehicleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    protected $vehicleService;

    protected $vehicleRepository;

    public function __construct(VehicleService $vehicleService, VehicleRepositoryInterface $vehicleRepository)
    {
        $this->vehicleService = $vehicleService;
        $this->vehicleRepository = $vehicleRepository;
    }

    public function index(): JsonResponse
    {
        $vehicles = $this->vehicleRepository->getUserVehicles(Auth::id());

        return response()->json(VehicleResource::collection($vehicles));
    }

    public function store(StoreVehicleRequest $request): JsonResponse
    {
        $vehicle = $this->vehicleService->createVehicle($request->validated());

        return response()->json([
            'message' => 'Vehicle added successfully',
            'vehicle' => new VehicleResource($vehicle),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $vehicle = $this->vehicleRepository->findById($id);
        if (! $vehicle) {
            return response()->json(['message' => 'Vehicle not found'], 404);
        }

        $this->authorize('view', $vehicle);

        return response()->json(new VehicleResource($vehicle));
    }

    public function updateMileage(Request $request, int $id): JsonResponse
    {
        $request->validate(['mileage' => 'required|integer|min:0']);

        $vehicle = $this->vehicleRepository->findById($id);
        if (! $vehicle) {
            return response()->json(['message' => 'Vehicle not found'], 404);
        }

        $this->authorize('update', $vehicle);

        $this->vehicleService->updateMileage($vehicle, $request->mileage);

        return response()->json(['message' => 'Mileage updated successfully']);
    }

    public function showByVin(string $vin): JsonResponse
    {
        // Search for existing vehicle with history
        $vehicle = Vehicle::with(['brand', 'model', 'owner', 'history'])
            ->where('vin', $vin)
            ->first();

        if ($vehicle) {
            return response()->json([
                'exists' => true,
                'data' => new VehicleResource($vehicle),
                'history_count' => $vehicle->history->count(),
            ]);
        }

        // If not found, return generic data simulation (or call external API)
        return response()->json([
            'exists' => false,
            'message' => 'Vehicle not registered in Piiston yet.',
            'suggested_data' => [
                'brand' => 'Unknown',
                'model' => 'Detected via VIN',
                'vin' => $vin,
            ],
        ]);
    }
}
