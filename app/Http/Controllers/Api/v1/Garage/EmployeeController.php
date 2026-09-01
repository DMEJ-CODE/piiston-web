<?php

namespace App\Http\Controllers\Api\v1\Garage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Garage\StoreEmployeeRequest;
use App\Models\Garages\GarageBranch;
use App\Models\Garages\GarageEmployee;
use App\Services\Garages\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct(protected EmployeeService $employeeService) {}

    public function index(Request $request, GarageBranch $branch): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $employees = $this->employeeService->getBranchEmployees($branch);

        return response()->json($employees);
    }

    public function store(StoreEmployeeRequest $request, GarageBranch $branch): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $employee = $this->employeeService->hireEmployee($branch, $request->user(), $request->validated());

        return response()->json($employee, 201);
    }

    public function show(GarageBranch $branch, $employee): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $employee = GarageEmployee::where('branch_id', $branch->id)
            ->with(['user', 'department'])
            ->findOrFail((int) $employee);

        return response()->json($employee);
    }

    public function update(StoreEmployeeRequest $request, GarageBranch $branch, $employee): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $employee = GarageEmployee::where('branch_id', $branch->id)
            ->findOrFail((int) $employee);

        $this->employeeService->updateEmployee($employee, $request->validated());

        return response()->json($employee);
    }

    public function destroy(GarageBranch $branch, $employee): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $employee = GarageEmployee::where('branch_id', $branch->id)
            ->findOrFail((int) $employee);

        $this->employeeService->terminateEmployee($employee);

        return response()->json(null, 204);
    }
}
