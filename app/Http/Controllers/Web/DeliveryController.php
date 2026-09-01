<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Garages\RepairOrder;
use App\Models\Workflows\VehicleDelivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryController extends Controller
{
    public function create(Request $request, $repairId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('operate', $branch);

        $repair = RepairOrder::where('branch_id', $branch->id)
            ->with(['vehicle', 'garageCustomer.user', 'checkIn'])
            ->findOrFail($repairId);

        return view('garage.deliveries.create', [
            'repair' => $repair,
            'branch' => $branch,
        ]);
    }

    public function store(Request $request, $repairId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('operate', $branch);

        $repair = RepairOrder::where('branch_id', $branch->id)->findOrFail($repairId);

        $request->validate([
            'mileage_at_delivery' => ['required', 'integer', 'min:'.($repair->checkIn->mileage ?? 0)],
            'notes' => ['nullable', 'string', 'max:2000'],
            'checklist' => ['nullable', 'array'],
            'signature_data' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($repair, $request) {
            $delivery = VehicleDelivery::create([
                'repair_order_id' => $repair->id,
                'delivered_by' => auth()->id(),
                'delivery_date' => now(),
                'customer_received_at' => now(),
                'notes' => $request->notes,
                'mileage_at_delivery' => $request->mileage_at_delivery,
                'checklist' => $request->checklist,
            ]);

            if ($request->filled('signature_data')) {
                // Save signature logic...
            }

            $repair->update([
                'status' => RepairOrder::STATUS_DELIVERED,
                'closed_at' => now(),
            ]);
        });

        return redirect()->route('garage.repairs.show', $repair->id)->with('success', 'Véhicule livré avec succès.');
    }
}
