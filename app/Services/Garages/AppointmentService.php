<?php

namespace App\Services\Garages;

use App\Models\Garages\GarageAppointment;
use App\Models\Garages\GarageBranch;
use Carbon\CarbonInterface;

class AppointmentService
{
    public function createAppointment(GarageBranch $branch, array $data): GarageAppointment
    {
        return $branch->appointments()->create($data);
    }

    public function findAppointment(int $id): ?GarageAppointment
    {
        return GarageAppointment::with(['customer', 'vehicle', 'service', 'request'])->find($id);
    }

    public function updateAppointment(GarageAppointment $appointment, array $data): bool
    {
        return $appointment->update($data);
    }

    public function cancelAppointment(GarageAppointment $appointment): bool
    {
        return $appointment->update(['status' => 'CANCELLED']);
    }

    public function confirmAppointment(GarageAppointment $appointment): bool
    {
        return $appointment->update(['status' => 'CONFIRMED']);
    }

    public function getAvailableSlots(GarageBranch $branch, Carbon $date, int $durationMinutes = 60): array
    {
        $dayStart = $date->copy()->startOfDay();
        $dayEnd = $date->copy()->endOfDay();

        $existingAppointments = GarageAppointment::where('branch_id', $branch->id)
            ->whereDate('scheduled_date', $date->toDateString())
            ->whereIn('status', ['CONFIRMED', 'SCHEDULED'])
            ->orderBy('scheduled_date')
            ->get(['scheduled_date', 'duration_minutes']);

        $slots = [];
        $current = $dayStart->copy()->setHour(8)->setMinute(0);
        $endOfDay = $dayStart->copy()->setHour(18)->setMinute(0);

        while ($current->lessThan($endOfDay)) {
            $slotEnd = $current->copy()->addMinutes($durationMinutes);
            if ($slotEnd->greaterThan($endOfDay)) {
                break;
            }

            $isAvailable = $existingAppointments->every(function ($apt) use ($current, $slotEnd) {
                $aptStart = Carbon::parse($apt->scheduled_date);
                $aptEnd = $aptStart->copy()->addMinutes($apt->duration_minutes);

                return $slotEnd->lessThanOrEqualTo($aptStart) || $current->greaterThanOrEqualTo($aptEnd);
            });

            if ($isAvailable) {
                $slots[] = $current->format('H:i');
            }

            $current->addMinutes(30);
        }

        return $slots;
    }

    public function getBranchAppointments(GarageBranch $branch, CarbonInterface $from, CarbonInterface $to, int $perPage = 15)
    {
        return GarageAppointment::where('branch_id', $branch->id)
            ->whereBetween('scheduled_date', [$from, $to])
            ->with(['customer', 'vehicle', 'service'])
            ->paginate($perPage);
    }
}
