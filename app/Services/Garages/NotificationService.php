<?php

namespace App\Services\Garages;

use App\Models\Garages\GarageAppointment;
use App\Models\Garages\GarageBranch;
use App\Models\Garages\GarageInvoice;
use App\Models\Garages\RepairOrder;
use App\Models\Garages\RepairPart;
use App\Models\Notifications\Notification;
use App\Models\Notifications\NotificationType;

class NotificationService
{
    public function notifyRepairStatusChanged(RepairOrder $repair, string $oldStatus, string $newStatus): void
    {
        $message = "Repair #{$repair->id} status changed from {$oldStatus} to {$newStatus}.";

        if ($repair->garageCustomer && $repair->garageCustomer->user_id) {
            $this->createNotification($repair->garageCustomer->user_id, 'repair_status_updated', $message, $repair);
        }

        if ($repair->mechanic) {
            $this->createNotification($repair->mechanic->id, 'repair_status_updated', $message, $repair);
        }
    }

    public function notifyEstimateReady(RepairOrder $repair): void
    {
        if ($repair->garageCustomer && $repair->garageCustomer->user_id) {
            $this->createNotification($repair->garageCustomer->user_id, 'estimate_ready', 'Your repair estimate is ready for review.', $repair);
        }
    }

    public function notifyAppointmentReminder(GarageAppointment $appointment): void
    {
        if ($appointment->customer && $appointment->customer->user_id) {
            $this->createNotification($appointment->customer->user_id, 'appointment_reminder', 'You have an upcoming appointment.', $appointment);
        }
    }

    public function notifyLowStock(GarageBranch $branch, RepairPart $part): void
    {
        $owner = $branch->company->owner;
        if ($owner) {
            $this->createNotification($owner->id, 'low_stock_alert', "Low stock alert: {$part->name}", $part);
        }
    }

    public function notifyPaymentReceived(GarageInvoice $invoice): void
    {
        if ($invoice->customer && $invoice->customer->user_id) {
            $this->createNotification($invoice->customer->user_id, 'payment_received', 'Payment received successfully.', $invoice);
        }
    }

    protected function createNotification(int $userId, string $type, string $message, $reference): void
    {
        $notificationType = NotificationType::where('name', $type)->first();
        if (! $notificationType) {
            $notificationType = NotificationType::create(['name' => $type, 'description' => ucfirst(str_replace('_', ' ', $type))]);
        }

        Notification::create([
            'user_id' => $userId,
            'type_id' => $notificationType->id,
            'title' => ucfirst(str_replace('_', ' ', $type)),
            'message' => $message,
            'priority' => 'normal',
            'reference_type' => get_class($reference),
            'reference_id' => $reference->id,
        ]);
    }
}
