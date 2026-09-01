<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Garages\AppointmentResource;
use App\Models\Garages\GarageAppointment;
use App\Models\Garages\GarageBranch;
use App\Models\Garages\GarageCustomer;
use App\Models\Vehicles\Vehicle;
use App\Services\Identity\ActivityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    protected $activityService;

    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    public function index(): JsonResponse
    {
        $appointments = GarageAppointment::whereHas('vehicle', function ($query) {
            $query->where('owner_id', Auth::id());
        })->with([
            'branch',
            'vehicle.brand',
            'vehicle.model',
            'vehicle.health',
            'vehicle.images',
            'service',
        ])->orderBy('scheduled_date', 'desc')->get();

        return response()->json(AppointmentResource::collection($appointments));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'branch_id' => 'nullable|exists:garage_branches,id',
            'garage_id' => 'nullable|exists:garage_companies,id', // Fallback
            'service_id' => 'required|exists:garage_services,id',
            'scheduled_date' => 'required', // Can be full datetime or just date
            'scheduled_time' => 'nullable|string',
            'request_id' => 'nullable|exists:service_requests,id',
            'notes' => 'nullable|string',
        ]);

        $branchId = $validated['branch_id'] ?? null;

        if (! $branchId && isset($validated['garage_id'])) {
            $branchId = GarageBranch::where('company_id', $validated['garage_id'])->first()?->id;
        }

        if (! $branchId) {
            return response()->json(['message' => 'Branch could not be resolved'], 422);
        }

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        if ($vehicle->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized vehicle'], 403);
        }

        // Ensure customer record exists for this branch
        $customer = GarageCustomer::firstOrCreate([
            'branch_id' => $branchId,
            'user_id' => Auth::id(),
        ], [
            'customer_type' => 'Individual',
        ]);

        // Merge date and time if needed
        $scheduledAt = $validated['scheduled_date'];
        if (isset($validated['scheduled_time']) && ! str_contains($scheduledAt, ':')) {
            $scheduledAt = date('Y-m-d H:i:s', strtotime($scheduledAt.' '.$validated['scheduled_time']));
        }

        $appointment = GarageAppointment::create([
            'request_id' => $validated['request_id'] ?? null,
            'branch_id' => $branchId,
            'customer_id' => $customer->id,
            'vehicle_id' => $validated['vehicle_id'],
            'service_id' => $validated['service_id'],
            'scheduled_date' => $scheduledAt,
            'status' => 'REQUESTED',
            'notes' => $validated['notes'] ?? null,
        ]);

        // Log activity
        $this->activityService->log(
            Auth::user(),
            'APPOINTMENT_BOOKED',
            'Rendez-vous réservé',
            "Votre rendez-vous pour {$appointment->service->name} le {$appointment->scheduled_date} a été enregistré.",
            $appointment
        );

        return response()->json([
            'message' => 'Appointment booked successfully',
            'appointment' => new AppointmentResource($appointment->load(['branch', 'service', 'vehicle'])),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $appointment = GarageAppointment::with(['branch', 'vehicle', 'service'])->findOrFail($id);

        if ($appointment->vehicle->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($appointment);
    }

    public function confirm(int $id): JsonResponse
    {
        $appointment = GarageAppointment::findOrFail($id);

        if ($appointment->vehicle->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $appointment->update(['status' => GarageAppointment::STATUS_CONFIRMED]);

        // Log activity
        $this->activityService->log(
            Auth::user(),
            'APPOINTMENT_CONFIRMED',
            'Rendez-vous confirmé',
            "Vous avez confirmé le rendez-vous pour le {$appointment->scheduled_date}.",
            $appointment
        );

        return response()->json(['message' => 'Appointment confirmed successfully']);
    }

    public function cancel(int $id): JsonResponse
    {
        $appointment = GarageAppointment::findOrFail($id);

        if ($appointment->vehicle->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $appointment->update(['status' => 'CANCELLED']);

        return response()->json(['message' => 'Appointment cancelled']);
    }
}
