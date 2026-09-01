<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Workflows\CreateRepairOrderRequest;
use App\Http\Resources\Workflows\RepairResource;
use App\Repositories\Workflows\RepairRepositoryInterface;
use App\Services\Workflows\RepairService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RepairController extends Controller
{
    protected $repairService;

    protected $repairRepository;

    public function __construct(RepairService $repairService, RepairRepositoryInterface $repairRepository)
    {
        $this->repairService = $repairService;
        $this->repairRepository = $repairRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $repairs = $this->repairRepository->getUserRepairs(Auth::id());

        return response()->json(RepairResource::collection($repairs));
    }

    public function store(CreateRepairOrderRequest $request): JsonResponse
    {
        $repair = $this->repairService->createRepairOrder($request->validated());

        return response()->json([
            'message' => 'Repair order created successfully',
            'repair' => new RepairResource($repair),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $repair = $this->repairRepository->findById($id);
        if (! $repair) {
            return response()->json(['message' => 'Repair not found'], 404);
        }

        Gate::authorize('view', $repair);

        return response()->json(new RepairResource($repair->load(['vehicle', 'garageCustomer.user', 'mechanic', 'diagnosis', 'estimate', 'progress'])));
    }

    public function history(int $id): JsonResponse
    {
        $history = $this->repairRepository->getHistory($id);

        return response()->json($history);
    }
}
