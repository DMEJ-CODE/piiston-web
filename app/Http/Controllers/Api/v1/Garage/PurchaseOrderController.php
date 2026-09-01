<?php

namespace App\Http\Controllers\Api\v1\Garage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Garage\StorePurchaseOrderRequest;
use App\Models\Garages\GarageBranch;
use App\Models\Garages\GaragePurchaseOrder;
use App\Services\Garages\PurchaseOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function __construct(protected PurchaseOrderService $purchaseOrderService) {}

    public function index(Request $request, GarageBranch $branch): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $purchaseOrders = $this->purchaseOrderService->getBranchPurchaseOrders($branch);

        return response()->json($purchaseOrders);
    }

    public function store(StorePurchaseOrderRequest $request, GarageBranch $branch): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $purchaseOrder = $this->purchaseOrderService->createPurchaseOrder($branch, $request->user(), $request->validated());

        return response()->json($purchaseOrder, 201);
    }

    public function show(GarageBranch $branch, $purchaseOrder): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $purchaseOrder = GaragePurchaseOrder::where('branch_id', $branch->id)
            ->with(['supplier', 'items'])
            ->findOrFail((int) $purchaseOrder);

        return response()->json($purchaseOrder);
    }

    public function receive(Request $request, GarageBranch $branch, $purchaseOrder): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $purchaseOrder = GaragePurchaseOrder::where('branch_id', $branch->id)
            ->findOrFail((int) $purchaseOrder);

        $request->validate([
            'items' => ['required', 'array'],
            'items.*' => ['required', 'integer', 'min:0'],
        ]);

        $this->purchaseOrderService->receiveItems($purchaseOrder, $request->items);

        return response()->json(['message' => 'Items received successfully']);
    }
}
