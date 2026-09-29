<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mechanics\StoreMechanicRequest;
use App\Http\Requests\Mechanics\UpdateMechanicRequest;
use App\Http\Resources\Mechanics\MechanicResource;
use App\Models\Mechanics\MechanicSkill;
use App\Models\Mechanics\MechanicType;
use App\Repositories\Mechanics\MechanicRepositoryInterface;
use App\Services\Mechanics\MechanicService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MechanicController extends Controller
{
    protected $mechanicService;

    protected $mechanicRepository;

    public function __construct(MechanicService $mechanicService, MechanicRepositoryInterface $mechanicRepository)
    {
        $this->mechanicService = $mechanicService;
        $this->mechanicRepository = $mechanicRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $mechanics = $this->mechanicRepository->search($request->all());

        return response()->json(MechanicResource::collection($mechanics)->response()->getData(true));
    }

    public function store(StoreMechanicRequest $request): JsonResponse
    {
        $profile = $this->mechanicService->createProfile($request->validated());

        return response()->json([
            'message' => 'Mechanic profile created successfully',
            'profile' => new MechanicResource($profile->load('user', 'type')),
        ], 201);
    }

    public function update(UpdateMechanicRequest $request): JsonResponse
    {
        $success = $this->mechanicService->updateProfile(Auth::id(), $request->validated());

        if (! $success) {
            return response()->json(['message' => 'Failed to update profile or profile not found'], 404);
        }

        $profile = $this->mechanicRepository->findByUserId(Auth::id());

        return response()->json([
            'message' => 'Mechanic profile updated successfully',
            'profile' => new MechanicResource($profile->load('user', 'type')),
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $profile = $this->mechanicRepository->findById($id);
        if (! $profile) {
            return response()->json(['message' => 'Mechanic not found'], 404);
        }

        return response()->json(new MechanicResource($profile));
    }

    public function myProfile(): JsonResponse
    {
        $profile = $this->mechanicRepository->findByUserId(Auth::id());
        if (! $profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        return response()->json(new MechanicResource($profile->load(['skills', 'certifications', 'type', 'user'])));
    }

    public function assignments(): JsonResponse
    {
        $assignments = $this->mechanicService->getMyAssignments();

        return response()->json($assignments);
    }

    public function listInvitations(): JsonResponse
    {
        $invitations = $this->mechanicService->getPendingInvitations(Auth::user()->email);

        return response()->json($invitations);
    }

    public function acceptInvitation(int $id): JsonResponse
    {
        try {
            $this->mechanicService->acceptInvitation($id, Auth::id());

            return response()->json(['message' => 'Invitation accepted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function declineInvitation(int $id): JsonResponse
    {
        $this->mechanicService->declineInvitation($id, Auth::id());

        return response()->json(['message' => 'Invitation declined']);
    }

    public function types(): JsonResponse
    {
        return response()->json(MechanicType::all());
    }

    public function skills(): JsonResponse
    {
        return response()->json(MechanicSkill::all());
    }
}
