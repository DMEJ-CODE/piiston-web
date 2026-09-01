<?php

namespace App\Services\Garages;

use App\Models\Garages\RepairOrder;
use App\Models\Garages\WarrantyClaim;
use Illuminate\Support\Facades\DB;

class WarrantyService
{
    public function resolveClaim(WarrantyClaim $claim, string $resolution, ?float $approvedAmount = null)
    {
        return DB::transaction(function () use ($claim, $resolution, $approvedAmount) {
            $claim->update([
                'status' => 'RESOLVED',
                'resolution_notes' => $resolution,
                'claim_amount' => $approvedAmount ?? $claim->claim_amount,
            ]);

            // If the resolution requires a new repair, we could link it here
            return $claim;
        });
    }

    public function checkWarrantyCoverage(RepairOrder $repair): bool
    {
        // Check if there is a previous repair for the same vehicle/parts that is still under warranty
        $vehicle = $repair->vehicle;

        $lastRepair = RepairOrder::where('vehicle_id', $vehicle->id)
            ->where('status', RepairOrder::STATUS_DELIVERED)
            ->where('closed_at', '>', now()->subMonths(6)) // Example 6 months warranty
            ->orderByDesc('closed_at')
            ->first();

        return (bool) $lastRepair;
    }
}
