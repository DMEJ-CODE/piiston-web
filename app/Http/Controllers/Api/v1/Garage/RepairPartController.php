<?php

namespace App\Http\Controllers\Api\v1\Garage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Garage\StoreRepairPartRequest;
use App\Http\Resources\Garages\RepairPartResource;
use App\Models\Garages\GarageBranch;
use App\Models\Garages\RepairPart;
use App\Services\Garages\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RepairPartController extends Controller
{
    public function __construct(protected InventoryService $inventoryService) {}

    public function index(Request $request, GarageBranch $branch): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $parts = $this->inventoryService->getBranchInventory($branch);

        return response()->json($parts);
    }

    public function store(StoreRepairPartRequest $request, GarageBranch $branch): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $part = $this->inventoryService->createInventoryItem($branch, $request->validated());

        return response()->json(new RepairPartResource($part), 201);
    }

    public function show(GarageBranch $branch, $part): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $part = RepairPart::where('branch_id', $branch->id)->findOrFail((int) $part);

        return response()->json(new RepairPartResource($part));
    }

    public function update(StoreRepairPartRequest $request, GarageBranch $branch, $part): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $part = RepairPart::where('branch_id', $branch->id)->findOrFail((int) $part);
        $part->update($request->validated());

        return response()->json(new RepairPartResource($part));
    }

    public function destroy(GarageBranch $branch, $part): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $part = RepairPart::where('branch_id', $branch->id)->findOrFail((int) $part);
        $part->delete();

        return response()->json(null, 204);
    }

    public function lowStock(GarageBranch $branch): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $items = RepairPart::where('branch_id', $branch->id)
            ->whereColumn('stock_quantity', '<=', 'minimum_stock')
            ->where('is_active', true)
            ->get();

        return response()->json(RepairPartResource::collection($items));
    }
}
