<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Workflows\SubmitDiagnosisRequest;
use App\Http\Requests\Workflows\SubmitEstimateRequest;
use App\Http\Resources\Workflows\DiagnosisResource;
use App\Http\Resources\Workflows\EstimateResource;
use App\Repositories\Workflows\RepairRepositoryInterface;
use App\Services\Workflows\RepairService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class RepairWorkflowController extends Controller
{
    protected $repairService;

    protected $repairRepository;

    public function __construct(RepairService $repairService, RepairRepositoryInterface $repairRepository)
    {
        $this->repairService = $repairService;
        $this->repairRepository = $repairRepository;
    }

    public function submitDiagnosis(SubmitDiagnosisRequest $request, int $id): JsonResponse
    {
        $repair = $this->repairRepository->findById($id);
        if (! $repair) {
            return response()->json(['message' => 'Repair not found'], 404);
        }

        Gate::authorize('update', $repair);

        $diagnosis = $this->repairService->submitDiagnosis($repair, $request->validated());

        return response()->json([
            'message' => 'Diagnosis submitted successfully',
            'diagnosis' => new DiagnosisResource($diagnosis),
        ]);
    }

    public function submitEstimate(SubmitEstimateRequest $request, int $id): JsonResponse
    {
        $repair = $this->repairRepository->findById($id);
        if (! $repair) {
            return response()->json(['message' => 'Repair not found'], 404);
        }

        Gate::authorize('update', $repair);

        $estimate = $this->repairService->submitEstimate($repair, $request->validated());

        return response()->json([
            'message' => 'Estimate generated successfully',
            'estimate' => new EstimateResource($estimate),
        ]);
    }

    public function approve(int $id): JsonResponse
    {
        $repair = $this->repairRepository->findById($id);
        if (! $repair) {
            return response()->json(['message' => 'Repair not found'], 404);
        }

        Gate::authorize('approve', $repair);

        $this->repairService->approveEstimate($repair);

        return response()->json(['message' => 'Repair estimate approved. Work will begin soon.']);
    }

    public function approveTask(int $id, int $taskId): JsonResponse
    {
        $repair = $this->repairRepository->findById($id);
        Gate::authorize('approve', $repair);

        $task = $repair->tasks()->findOrFail($taskId);
        $task->update(['status' => 'APPROVED']);

        return response()->json(['message' => 'Task approved']);
    }

    public function rejectTask(int $id, int $taskId): JsonResponse
    {
        $repair = $this->repairRepository->findById($id);
        Gate::authorize('approve', $repair);

        $task = $repair->tasks()->findOrFail($taskId);
        $task->update(['status' => 'REJECTED']);

        return response()->json(['message' => 'Task rejected']);
    }
}
