<?php

namespace App\Http\Controllers\Api\v1\Garage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Garage\StoreWarrantyClaimRequest;
use App\Models\Garages\GarageBranch;
use App\Models\Garages\WarrantyClaim;
use App\Services\Garages\AuditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WarrantyClaimController extends Controller
{
    public function __construct(protected AuditService $auditService) {}

    public function index(Request $request, GarageBranch $branch): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $claims = WarrantyClaim::where('branch_id', $branch->id)
            ->with(['repairOrder', 'customer', 'vehicle'])
            ->paginate(15);

        return response()->json($claims);
    }

    public function store(StoreWarrantyClaimRequest $request, GarageBranch $branch): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $claim = WarrantyClaim::create($request->validated());

        $this->auditService->log(WarrantyClaim::class, $claim->id, 'created', null, $claim->toArray());

        return response()->json($claim, 201);
    }

    public function show(GarageBranch $branch, $warrantyClaim): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $claim = WarrantyClaim::where('branch_id', $branch->id)
            ->with(['repairOrder', 'customer', 'vehicle'])
            ->findOrFail((int) $warrantyClaim);

        return response()->json($claim);
    }

    public function update(StoreWarrantyClaimRequest $request, GarageBranch $branch, $warrantyClaim): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $claim = WarrantyClaim::where('branch_id', $branch->id)->findOrFail((int) $warrantyClaim);
        $oldValues = $claim->toArray();
        $claim->update($request->validated());

        $this->auditService->log(WarrantyClaim::class, $claim->id, 'updated', $oldValues, $claim->toArray());

        return response()->json($claim);
    }

    public function destroy(GarageBranch $branch, $warrantyClaim): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $claim = WarrantyClaim::where('branch_id', $branch->id)->findOrFail((int) $warrantyClaim);
        $claim->delete();

        return response()->json(null, 204);
    }
}
