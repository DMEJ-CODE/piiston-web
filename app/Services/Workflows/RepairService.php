<?php

namespace App\Services\Workflows;

use App\Models\Garages\RepairOrder;
use App\Models\Garages\RepairPart;
use App\Models\Workflows\ServiceRequest;
use App\Repositories\Workflows\RepairRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RepairService
{
    protected $repairRepository;

    public function __construct(RepairRepositoryInterface $repairRepository)
    {
        $this->repairRepository = $repairRepository;
    }

    public function createRequest(array $data): ServiceRequest
    {
        return ServiceRequest::create($data + [
            'user_id' => Auth::id(),
            'status' => 'PENDING',
        ]);
    }

    public function createRepairOrder(array $data): RepairOrder
    {
        $repair = $this->repairRepository->create($data + [
            'status' => RepairOrder::STATUS_REQUESTED,
            'opened_at' => now(),
        ]);

        $this->logProgress($repair, 'Repair order created and initial inspection requested.');

        return $repair;
    }

    public function submitDiagnosis(RepairOrder $repair, array $data)
    {
        return DB::transaction(function () use ($repair, $data) {
            $diagnosis = $repair->diagnosis()->updateOrCreate(['repair_order_id' => $repair->id], $data + [
                'mechanic_id' => Auth::id(),
            ]);

            $repair->update(['status' => RepairOrder::STATUS_DIAGNOSIS]);
            $this->logProgress($repair, 'Diagnostic completed. Symptoms and recommended actions identified.');

            return $diagnosis;
        });
    }

    public function submitEstimate(RepairOrder $repair, array $data)
    {
        return DB::transaction(function () use ($repair, $data) {
            $estimate = $repair->estimate()->updateOrCreate(['repair_order_id' => $repair->id], $data + [
                'created_by' => Auth::id(),
                'status' => 'DRAFT',
            ]);

            $repair->update(['status' => RepairOrder::STATUS_QUOTE_CREATED]);
            $this->logProgress($repair, 'Quotation generated and sent for internal review.');

            return $estimate;
        });
    }

    public function approveEstimate(RepairOrder $repair)
    {
        return DB::transaction(function () use ($repair) {
            $repair->update(['status' => RepairOrder::STATUS_APPROVED]);
            if ($repair->estimate) {
                $repair->estimate->update(['status' => 'APPROVED']);
            }

            $this->logProgress($repair, 'Quotation approved by the customer. Repair is now scheduled.');

            return true;
        });
    }

    public function updateStatus(RepairOrder $repair, string $status, ?string $message = null)
    {
        $repair->update(['status' => $status]);
        $this->logProgress($repair, $message ?? "Repair status updated to $status.");
    }

    public function addTask(RepairOrder $repair, array $data)
    {
        return DB::transaction(function () use ($repair, $data) {
            $task = $repair->tasks()->create($data + [
                'status' => 'pending',
            ]);

            $this->logProgress($repair, "New task added: {$task->title}");

            return $task;
        });
    }

    public function addPartUsage(RepairOrder $repair, array $data)
    {
        return DB::transaction(function () use ($repair, $data) {
            $partUsage = $repair->parts()->create($data);

            // Deduct from inventory if part_id is provided
            if (isset($data['part_id'])) {
                $part = RepairPart::find($data['part_id']);
                if ($part) {
                    $part->decrement('stock_quantity', $data['quantity']);
                }
            }

            $this->logProgress($repair, "Part added to repair: {$partUsage->part_name} (Qty: {$partUsage->quantity})");

            return $partUsage;
        });
    }

    public function completeRepair(RepairOrder $repair)
    {
        return DB::transaction(function () use ($repair) {
            $repair->update([
                'status' => RepairOrder::STATUS_COMPLETED,
                'closed_at' => now(),
            ]);

            $this->logProgress($repair, 'Repair work completed. Ready for quality check.');

            // Automatically add to Vehicle Maintenance Log (Carnet de soin)
            $repair->vehicle->maintenances()->create([
                'service_name' => $repair->problem_description ?? 'Réparation générale',
                'description' => 'Réparation effectuée au garage '.$repair->branch->name,
                'mileage' => $repair->vehicle->current_mileage,
                'cost' => $repair->final_cost ?? $repair->estimated_cost,
                'date' => now(),
                'status' => 'COMPLETED',
                'branch_id' => $repair->branch_id,
                'technician_id' => $repair->assigned_mechanic_id,
            ]);

            return true;
        });
    }

    public function generateInvoice(RepairOrder $repair)
    {
        return DB::transaction(function () use ($repair) {
            $partsTotal = $repair->parts()->sum('total_price');
            // Assuming tasks have a cost field or we calculate based on duration
            $laborTotal = $repair->tasks()->sum('actual_time_minutes') * 100; // Example: 100 per minute

            $subtotal = $partsTotal + $laborTotal;
            $tax = $subtotal * 0.1925; // Example VAT for Cameroon
            $total = $subtotal + $tax;

            $invoice = $repair->invoice()->create([
                'customer_id' => $repair->customer_id,
                'amount' => $subtotal,
                'tax' => $tax,
                'discount' => 0,
                'total_payable' => $total,
                'status' => 'unpaid',
            ]);

            $this->logProgress($repair, "Invoice #{$invoice->id} generated.");

            return $invoice;
        });
    }

    public function performQualityCheck(RepairOrder $repair, array $data)
    {
        return DB::transaction(function () use ($repair, $data) {
            $check = $repair->qualityCheck()->create($data + [
                'checked_by' => Auth::id(),
                'check_date' => now(),
            ]);

            if ($data['result'] === 'PASS') {
                $repair->update(['status' => RepairOrder::STATUS_COMPLETED]);
                $this->logProgress($repair, 'Quality control passed. Vehicle is ready for delivery.');
            } else {
                $repair->update(['status' => RepairOrder::STATUS_IN_PROGRESS]);
                $this->logProgress($repair, "Quality control failed: {$data['notes']}. Returning to repair.");
            }

            return $check;
        });
    }

    protected function logProgress(RepairOrder $repair, string $message)
    {
        $repair->progress()->create([
            'status' => $repair->status,
            'message' => $message,
        ]);
    }
}
