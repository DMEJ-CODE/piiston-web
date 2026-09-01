<?php

namespace App\Http\Controllers\Api\v1\Fleet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fleets\ExpenseRequest;
use App\Http\Resources\Fleets\ExpenseResource;
use App\Repositories\Fleets\FleetRepositoryInterface;
use App\Services\Fleets\FleetService;
use Illuminate\Http\JsonResponse;

class ExpenseController extends Controller
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
        $expenses = $this->fleetRepository->getFleetExpenses($fleetId);

        return response()->json(ExpenseResource::collection($expenses));
    }

    public function store(ExpenseRequest $request, int $fleetId): JsonResponse
    {
        $expense = $this->fleetService->recordExpense($fleetId, $request->validated());

        return response()->json([
            'message' => 'Expense recorded successfully',
            'expense' => new ExpenseResource($expense),
        ], 201);
    }
}
