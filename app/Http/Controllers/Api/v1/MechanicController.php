<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mechanics\StoreMechanicRequest;
use App\Http\Resources\Mechanics\MechanicResource;
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
}
