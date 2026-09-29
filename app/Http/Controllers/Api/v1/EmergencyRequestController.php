<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Maps\TrackingSession;
use App\Models\User;
use App\Models\Vehicles\Vehicle;
use App\Models\Workflows\EmergencyRequest;
use App\Services\Identity\ActivityService;
use App\Services\Notifications\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmergencyRequestController extends Controller
{
    protected $activityService;

    protected $notificationService;

    public function __construct(ActivityService $activityService, NotificationService $notificationService)
    {
        $this->activityService = $activityService;
        $this->notificationService = $notificationService;
    }

    public function index(): JsonResponse
    {
        $user = Auth::user();
        $query = EmergencyRequest::with(['vehicle.brand', 'vehicle.model', 'mechanic', 'branch']);

        if ($user->hasRole('VEHICLE_OWNER')) {
            $query->where('user_id', $user->id);
        } else {
            // Mechanics and Managers
            $query->where(function ($q) use ($user) {
                // Show requests where they are the assigned mechanic
                $q->where('assigned_mechanic_id', $user->id)
                  // OR Show new requests (broadcasts)
                    ->orWhere('status', 'REQUESTED')
                  // OR Show requests for branches they manage
                    ->orWhereHas('branch', function ($b) use ($user) {
                        $b->where('manager_id', $user->id);
                    });
            });
        }

        $requests = $query->orderBy('created_at', 'desc')->get();

        // Add tracking for active ones
        foreach ($requests as $request) {
            if ($request->status === 'ACCEPTED' || $request->status === 'IN_PROGRESS') {
                $request->tracking = $this->getTrackingData($request);
            }
        }

        return response()->json($requests);
    }

    public function active(): JsonResponse
    {
        $activeRequest = EmergencyRequest::where('user_id', Auth::id())
            ->whereIn('status', ['REQUESTED', 'ASSIGNED', 'IN_PROGRESS', 'ACCEPTED', 'ARRIVED'])
            ->with(['vehicle.brand', 'vehicle.model', 'mechanic', 'branch'])
            ->orderBy('created_at', 'desc')
            ->first();

        if ($activeRequest) {
            $activeRequest->tracking = $this->getTrackingData($activeRequest);
        }

        return response()->json($activeRequest);
    }

    protected function getTrackingData($emergency)
    {
        if (! $emergency->assigned_mechanic_id) {
            return null;
        }

        $session = TrackingSession::where('entity_type', 'MECHANIC')
            ->where('entity_id', $emergency->assigned_mechanic_id)
            ->where('status', 'active')
            ->with('liveLocation')
            ->latest()
            ->first();

        if (! $session || ! $session->liveLocation) {
            return null;
        }

        return [
            'latitude' => $session->liveLocation->latitude,
            'longitude' => $session->liveLocation->longitude,
            'last_updated' => $session->liveLocation->captured_at,
        ];
    }

    public function store(Request $request): JsonResponse
    {
        // Check if an active SOS already exists
        $existing = EmergencyRequest::where('user_id', Auth::id())
            ->whereIn('status', ['REQUESTED', 'ASSIGNED', 'IN_PROGRESS', 'ACCEPTED'])
            ->exists();

        if ($existing) {
            return response()->json([
                'message' => 'An active SOS request already exists for your account.',
            ], 422);
        }

        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'location' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius_km' => 'required|integer',
            'problem_description' => 'nullable|string',
        ]);

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        if ($vehicle->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized vehicle'], 403);
        }

        $emergency = EmergencyRequest::create([
            'user_id' => Auth::id(),
            'vehicle_id' => $validated['vehicle_id'],
            'location' => $validated['location'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'radius_km' => $validated['radius_km'],
            'problem_description' => $validated['problem_description'] ?? 'Assistance demandée.',
            'status' => 'REQUESTED',
        ]);

        // Log activity
        $this->activityService->log(
            Auth::user(),
            'SOS_REQUESTED',
            'Alerte SOS envoyée',
            "Votre demande d'assistance d'urgence pour {$emergency->vehicle->brand->name} a été transmise aux garages dans un rayon de {$emergency->radius_km}km.",
            $emergency
        );

        // Broadcast to Mechanics (Simulated: Notify all users with MECHANIC role for now)
        $mechanics = User::whereHas('roles', fn ($q) => $q->where('name', 'MECHANIC'))->get();
        foreach ($mechanics as $mechanic) {
            $this->notificationService->send(
                $mechanic,
                'NEW_SOS_ALERT',
                'Alerte SOS Proche',
                "Un véhicule {$vehicle->brand->name} a besoin d'assistance à {$emergency->location}.",
                ['reference_type' => 'EmergencyRequest', 'reference_id' => $emergency->id, 'category' => 'Repair', 'priority' => 'high']
            );
        }

        return response()->json([
            'message' => 'Emergency assistance requested. Help is on the way.',
            'request' => $emergency->load(['vehicle.brand', 'vehicle.model']),
        ], 201);
    }

    public function accept(Request $request, int $id): JsonResponse
    {
        $emergency = EmergencyRequest::findOrFail($id);

        if ($emergency->status !== 'REQUESTED') {
            return response()->json(['message' => 'This alert has already been taken or cancelled.'], 400);
        }

        $validated = $request->validate([
            'branch_id' => 'required|exists:garage_branches,id',
            'mechanic_id' => 'nullable|exists:users,id',
        ]);

        $emergency->update([
            'status' => 'ACCEPTED',
            'branch_id' => $validated['branch_id'],
            'assigned_mechanic_id' => $validated['mechanic_id'] ?? Auth::id(),
        ]);

        // Notify Owner
        $this->notificationService->send(
            $emergency->user,
            'SOS_ACCEPTED',
            'SOS Accepté',
            "Votre demande d'assistance a été acceptée par ".($emergency->branch->name ?? 'un mécanicien').'.',
            ['reference_type' => 'EmergencyRequest', 'reference_id' => $emergency->id, 'category' => 'Repair']
        );

        return response()->json([
            'message' => 'Alert accepted successfully.',
            'request' => $emergency->load(['user', 'vehicle', 'mechanic']),
        ]);
    }

    public function arrive(int $id): JsonResponse
    {
        $emergency = EmergencyRequest::findOrFail($id);
        $user = Auth::user();

        // Check if current user is the assigned mechanic
        if ($emergency->assigned_mechanic_id !== $user->id) {
            return response()->json(['message' => 'Only the assigned mechanic can confirm arrival.'], 403);
        }

        $emergency->update(['status' => 'ARRIVED']);

        // Notify Owner
        $this->notificationService->send(
            $emergency->user,
            'SOS_ARRIVED',
            'Dépanneur Arrivé',
            'Le mécanicien est arrivé à votre position.',
            ['reference_type' => 'EmergencyRequest', 'reference_id' => $emergency->id, 'category' => 'Repair']
        );

        // End tracking sessions for this SOS
        TrackingSession::where('entity_type', 'MECHANIC')
            ->where('entity_id', $user->id)
            ->where('status', 'active')
            ->update(['status' => 'completed', 'ended_at' => now()]);

        TrackingSession::where('entity_type', 'USER')
            ->where('entity_id', $emergency->user_id)
            ->where('status', 'active')
            ->update(['status' => 'completed', 'ended_at' => now()]);

        // Log activity for the owner
        $this->activityService->log(
            $emergency->user,
            'SOS_ARRIVED',
            'Assistance arrivée',
            'Le dépanneur est arrivé à votre position.',
            $emergency
        );

        return response()->json(['message' => 'Arrival confirmed. Status updated to ARRIVED.']);
    }

    public function complete(int $id): JsonResponse
    {
        $emergency = EmergencyRequest::with(['user', 'vehicle.brand'])->findOrFail($id);
        $user = Auth::user();

        if ($emergency->assigned_mechanic_id !== $user->id && ! ($emergency->branch && $emergency->branch->manager_id === $user->id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $emergency->update(['status' => 'COMPLETED', 'paid_at' => now()]);

        // Create the electronic logbook entry automatically
        $emergency->vehicle->maintenances()->create([
            'service_name' => 'Assistance SOS: '.($emergency->service_sheet['title'] ?? 'Dépannage'),
            'date' => now(),
            'mileage' => $emergency->service_sheet['mileage'] ?? $emergency->vehicle->mileage ?? 0,
            'cost' => $emergency->final_cost ?? $emergency->estimated_cost ?? 0,
            'description' => $emergency->service_sheet['findings'] ?? $emergency->problem_description,
            'status' => 'COMPLETED',
        ]);

        // Log activity for the owner
        $this->activityService->log(
            $emergency->user,
            'SOS_COMPLETED',
            'Intervention terminée',
            "L'intervention pour votre {$emergency->vehicle->brand->name} est terminée. Le carnet d'entretien a été mis à jour.",
            $emergency
        );

        // Notify Owner
        $this->notificationService->send(
            $emergency->user,
            'SOS_COMPLETED',
            'Intervention Terminée',
            "L'intervention est terminée. Merci d'avoir utilisé Piiston.",
            ['reference_type' => 'EmergencyRequest', 'reference_id' => $emergency->id, 'category' => 'Repair']
        );

        return response()->json(['message' => 'Intervention marked as completed and logbook generated.']);
    }

    public function submitServiceSheet(Request $request, int $id): JsonResponse
    {
        $emergency = EmergencyRequest::findOrFail($id);
        $user = Auth::user();

        if ($emergency->assigned_mechanic_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'findings' => 'required|string',
            'mileage' => 'required|integer',
            'estimated_cost' => 'required|numeric|min:0',
            'parts_needed' => 'nullable|array',
            'tasks' => 'nullable|array',
        ]);

        $emergency->update([
            'status' => 'ESTIMATE_PENDING',
            'service_sheet' => $validated,
            'estimated_cost' => $validated['estimated_cost'],
        ]);

        // Notify Owner
        $this->activityService->log(
            $emergency->user,
            'SOS_ESTIMATE_RECEIVED',
            'Diagnostic SOS reçu',
            'Le mécanicien a établi un diagnostic. Veuillez valider les travaux pour commencer les réparations.',
            $emergency
        );

        // If mechanic works for a garage, notify branch manager
        if ($emergency->branch && $emergency->branch->manager) {
            $this->activityService->log(
                $emergency->branch->manager,
                'SOS_ESTIMATE_SUBMITTED',
                'Nouveau diagnostic SOS',
                "Le mécanicien {$user->name} a soumis un diagnostic pour le SOS de {$emergency->user->name}.",
                $emergency
            );
        }

        return response()->json(['message' => 'Service sheet submitted. Waiting for owner approval.']);
    }

    public function approveEstimate(int $id): JsonResponse
    {
        $emergency = EmergencyRequest::findOrFail($id);
        if ($emergency->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($emergency->status !== 'ESTIMATE_PENDING') {
            return response()->json(['message' => 'No pending estimate to approve.'], 400);
        }

        $emergency->update(['status' => 'ACCEPTED']); // Move to accepted/in_progress again

        // Notify Mechanic
        $mechanic = User::find($emergency->assigned_mechanic_id);
        if ($mechanic) {
            $this->activityService->log(
                $mechanic,
                'SOS_APPROVED',
                'Travaux approuvés',
                'Le client a validé votre devis SOS. Vous pouvez commencer les réparations.',
                $emergency
            );
        }

        return response()->json(['message' => 'Estimate approved. Repairs can start.']);
    }

    public function decline(int $id): JsonResponse
    {
        $emergency = EmergencyRequest::findOrFail($id);
        $user = Auth::user();

        // Check authorization (Mechanic or Branch Manager)
        $isMechanic = $emergency->assigned_mechanic_id === $user->id;
        $isBranchManager = $emergency->branch && $emergency->branch->manager_id === $user->id;

        if (! $isMechanic && ! $isBranchManager) {
            return response()->json(['message' => 'Unauthorized to decline this intervention.'], 403);
        }

        if ($emergency->status === 'COMPLETED' || $emergency->status === 'ARRIVED') {
            return response()->json(['message' => 'Cannot decline a completed or arrived intervention.'], 400);
        }

        // Revert status to REQUESTED so other garages can see it
        $emergency->update([
            'status' => 'REQUESTED',
            'assigned_mechanic_id' => null,
            'branch_id' => null,
        ]);

        // End tracking sessions
        TrackingSession::where('entity_type', 'MECHANIC')
            ->where('entity_id', $user->id)
            ->where('status', 'active')
            ->update(['status' => 'cancelled', 'ended_at' => now()]);

        // Log activity for the owner
        $this->activityService->log(
            $emergency->user,
            'SOS_DECLINED',
            'Intervention annulée par le mécanicien',
            'Le mécanicien assigné ne peut plus intervenir. Votre demande est de nouveau visible par les autres dépanneurs.',
            $emergency
        );

        return response()->json(['message' => 'Intervention declined. SOS reverted to requested status.']);
    }

    public function show(int $id): JsonResponse
    {
        $emergency = EmergencyRequest::with(['vehicle.brand', 'vehicle.model', 'mechanic', 'branch'])->findOrFail($id);
        $user = Auth::user();

        // Authorization: Owner, Assigned Mechanic, or Branch Manager
        $isOwner = $emergency->user_id === $user->id;
        $isMechanic = $emergency->assigned_mechanic_id === $user->id;
        $isBranchManager = $emergency->branch && $emergency->branch->manager_id === $user->id;

        if (! $isOwner && ! $isMechanic && ! $isBranchManager) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $emergency->tracking = $this->getTrackingData($emergency);

        return response()->json($emergency);
    }

    public function cancel(int $id): JsonResponse
    {
        $emergency = EmergencyRequest::findOrFail($id);

        if ($emergency->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($emergency->status === 'COMPLETED' || $emergency->status === 'CANCELLED') {
            return response()->json(['message' => "Cannot cancel this request (Status: {$emergency->status})"], 400);
        }

        // Business rule: If already accepted, cancellation fee might apply
        $isAccepted = in_array($emergency->status, ['ACCEPTED', 'IN_PROGRESS']);

        $emergency->update(['status' => 'CANCELLED']);

        // Log activity
        $this->activityService->log(
            Auth::user(),
            'SOS_CANCELLED',
            'Alerte SOS annulée',
            $isAccepted
                ? "Vous avez annulé une assistance en cours. Des frais de déplacement peuvent s'appliquer."
                : "Votre demande d'assistance d'urgence a été annulée.",
            $emergency
        );

        return response()->json([
            'message' => 'Emergency request cancelled',
            'requires_fee' => $isAccepted,
            'fee_amount' => $isAccepted ? 5000 : 0, // Example: 5000 CFA
        ]);
    }
}
