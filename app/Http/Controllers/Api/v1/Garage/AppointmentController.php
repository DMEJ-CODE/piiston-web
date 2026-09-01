<?php

namespace App\Http\Controllers\Api\v1\Garage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Garage\StoreAppointmentRequest;
use App\Models\Garages\GarageBranch;
use App\Services\Garages\AppointmentService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function __construct(protected AppointmentService $appointmentService) {}

    public function index(Request $request, GarageBranch $branch): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $from = $request->filled('from') ? Carbon::parse($request->from) : now()->startOfDay();
        $to = $request->filled('to') ? Carbon::parse($request->to) : now()->addDays(30)->endOfDay();

        $appointments = $this->appointmentService->getBranchAppointments($branch, $from, $to);

        return response()->json($appointments);
    }

    public function store(StoreAppointmentRequest $request, GarageBranch $branch): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $appointment = $this->appointmentService->createAppointment($branch, $request->validated());

        return response()->json($appointment, 201);
    }

    public function show(GarageBranch $branch, $appointment): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $appointment = $this->appointmentService->findAppointment((int) $appointment);
        if (! $appointment || $appointment->branch_id !== $branch->id) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        return response()->json($appointment);
    }

    public function update(StoreAppointmentRequest $request, GarageBranch $branch, $appointment): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $appointment = $this->appointmentService->findAppointment((int) $appointment);
        if (! $appointment || $appointment->branch_id !== $branch->id) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        $this->appointmentService->updateAppointment($appointment, $request->validated());

        return response()->json($appointment);
    }

    public function confirm(GarageBranch $branch, $appointment): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $appointment = $this->appointmentService->findAppointment((int) $appointment);
        if (! $appointment || $appointment->branch_id !== $branch->id) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        $this->appointmentService->confirmAppointment($appointment);

        return response()->json(['message' => 'Appointment confirmed']);
    }

    public function repropose(Request $request, GarageBranch $branch, $appointment): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $request->validate([
            'scheduled_date' => 'required|date_format:Y-m-d H:i:s',
            'notes' => 'nullable|string',
        ]);

        $appointment = $this->appointmentService->findAppointment((int) $appointment);
        if (! $appointment || $appointment->branch_id !== $branch->id) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        $appointment->update([
            'scheduled_date' => $request->scheduled_date,
            'status' => 'PROPOSED',
            'proposed_by_id' => Auth::id(),
            'notes' => $request->notes,
        ]);

        return response()->json(['message' => 'New date proposed to customer']);
    }

    public function destroy(GarageBranch $branch, $appointment): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $appointment = $this->appointmentService->findAppointment((int) $appointment);
        if (! $appointment || $appointment->branch_id !== $branch->id) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        $this->appointmentService->cancelAppointment($appointment);

        return response()->json(null, 204);
    }

    public function slots(Request $request, GarageBranch $branch): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $request->validate([
            'date' => ['required', 'date'],
            'duration' => ['nullable', 'integer', 'min:15'],
        ]);

        $slots = $this->appointmentService->getAvailableSlots(
            $branch,
            Carbon::parse($request->date),
            $request->duration ?? 60
        );

        return response()->json($slots);
    }
}
