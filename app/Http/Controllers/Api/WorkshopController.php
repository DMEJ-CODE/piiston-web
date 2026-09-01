<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Garages\GarageBranch;
use App\Models\Garages\RepairOrder;

class WorkshopController extends Controller
{
    public function branchRepairOrders(GarageBranch $branch)
    {
        return response()->json(
            $branch->repairOrders()->with(['vehicle', 'customer.user', 'mechanic'])->get()
        );
    }

    public function repairOrderDetails(RepairOrder $repairOrder)
    {
        return response()->json(
            $repairOrder->load(['vehicle.brand', 'vehicle.model', 'tasks', 'parts', 'diagnosis', 'customer.user'])
        );
    }
}
