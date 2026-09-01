<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Workflows\RepairWarranty;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class WarrantyController extends Controller
{
    public function index(): JsonResponse
    {
        // Get warranties where the user is the owner of the vehicle in the linked repair order
        $warranties = RepairWarranty::whereHas('repairOrder', function ($query) {
            $query->where('customer_id', Auth::id());
        })
            ->with(['repairOrder.vehicle.brand', 'repairOrder.vehicle.model', 'repairOrder.branch'])
            ->orderBy('end_date', 'desc')
            ->get();

        return response()->json($warranties);
    }

    public function show(int $id): JsonResponse
    {
        $warranty = RepairWarranty::with(['repairOrder.vehicle', 'repairOrder.branch'])->findOrFail($id);

        if ($warranty->repairOrder->customer_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($warranty);
    }
}
