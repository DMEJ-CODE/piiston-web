<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Garages\StoreGarageRequest;
use App\Http\Resources\Garages\BranchNearbyResource;
use App\Http\Resources\Garages\GarageResource;
use App\Http\Resources\Garages\ServiceResource;
use App\Models\Garages\GarageBranch;
use App\Repositories\Garages\GarageRepositoryInterface;
use App\Services\Garages\GarageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GarageController extends Controller
{
    protected $garageService;

    protected $garageRepository;

    public function __construct(GarageService $garageService, GarageRepositoryInterface $garageRepository)
    {
        $this->garageService = $garageService;
        $this->garageRepository = $garageRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $garages = $this->garageRepository->search($request->all());

        return response()->json(GarageResource::collection($garages)->response()->getData(true));
    }

    public function store(StoreGarageRequest $request): JsonResponse
    {
        $garage = $this->garageService->createGarage($request->validated());

        return response()->json([
            'message' => 'Garage registration submitted successfully',
            'garage' => new GarageResource($garage),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $garage = $this->garageRepository->findById($id);
        if (! $garage) {
            return response()->json(['message' => 'Garage not found'], 404);
        }

        return response()->json(new GarageResource($garage));
    }

    public function nearby(Request $request): JsonResponse
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'radius' => 'nullable|integer',
        ]);

        $branches = $this->garageRepository->getNearby($request->lat, $request->lng, $request->radius ?? 10);

        return response()->json(BranchNearbyResource::collection($branches));
    }

    public function branchServices(int $branchId): JsonResponse
    {
        $branch = GarageBranch::findOrFail($branchId);

        return response()->json(ServiceResource::collection($branch->services));
    }
}
