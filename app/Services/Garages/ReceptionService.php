<?php

namespace App\Services\Garages;

use App\Models\Garages\GarageBranch;
use App\Models\Garages\RepairOrder;
use App\Models\Garages\VehicleCheckIn;
use Illuminate\Support\Facades\DB;

class ReceptionService
{
    /**
     * Complete the reception process by creating a Check-In and an optional Repair Order.
     */
    public function completeReception(GarageBranch $branch, array $data): VehicleCheckIn
    {
        return DB::transaction(function () use ($branch, $data) {
            // 1. Create Check-In
            $checkIn = VehicleCheckIn::create([
                'branch_id' => $branch->id,
                'vehicle_id' => $data['vehicle_id'],
                'customer_id' => $data['customer_id'],
                'appointment_id' => $data['appointment_id'] ?? null,
                'received_by' => auth()->id(),
                'arrival_date' => now(),
                'mileage' => $data['mileage'],
                'fuel_level' => $data['fuel_level'] ?? null,
                'vehicle_condition' => $data['vehicle_condition'] ?? null,
                'checklist' => $data['checklist'] ?? [],
                'notes' => $data['notes'] ?? null,
                'signature_path' => $data['signature_path'] ?? null,
                'status' => 'RECEIVED',
            ]);

            // 2. Handle Media (Photos/Videos)
            if (! empty($data['media'])) {
                foreach ($data['media'] as $mediaItem) {
                    $checkIn->media()->create([
                        'branch_id' => $branch->id,
                        'type' => $mediaItem['type'],
                        'path' => $mediaItem['path'],
                        'metadata' => $mediaItem['metadata'] ?? null,
                    ]);
                }
            }

            // 3. Automatically create a Repair Order if requested
            if (! empty($data['create_repair_order'])) {
                RepairOrder::create([
                    'check_in_id' => $checkIn->id,
                    'branch_id' => $branch->id,
                    'vehicle_id' => $data['vehicle_id'],
                    'customer_id' => $data['customer_id'],
                    'appointment_id' => $data['appointment_id'] ?? null,
                    'problem_description' => $data['notes'] ?? 'Reception check-in created.',
                    'status' => RepairOrder::STATUS_REQUESTED,
                    'status_history' => [
                        [
                            'status' => RepairOrder::STATUS_REQUESTED,
                            'changed_at' => now(),
                            'changed_by' => auth()->id(),
                            'notes' => 'Automatic creation from reception.',
                        ],
                    ],
                ]);
            }

            return $checkIn;
        });
    }
}
