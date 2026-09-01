<?php

namespace App\Http\Controllers\Api\v1\Garage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Garage\StoreBranchRequest;
use App\Http\Resources\Garages\BranchResource;
use App\Models\Garages\GarageCompany;
use App\Services\Garages\CustomerService;
use App\Services\Garages\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function __construct(
        protected EmployeeService $employeeService,
        protected CustomerService $customerService
    ) {}

    public function index(Request $request, GarageCompany $company): JsonResponse
    {
        $this->authorize('manage', $company);

        return response()->json(BranchResource::collection($company->branches));
    }

    public function store(StoreBranchRequest $request, GarageCompany $company): JsonResponse
    {
        $this->authorize('manage', $company);

        $branch = $company->branches()->create($request->validated());

        return response()->json(new BranchResource($branch), 201);
    }

    public function show(GarageCompany $company, GarageBranch $branch): JsonResponse
    {
        $this->authorize('manage', $company);

        return response()->json(new BranchResource($branch));
    }

    public function employees(Request $request, GarageCompany $company, GarageBranch $branch): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $employees = $this->employeeService->getBranchEmployees($branch);

        return response()->json($employees);
    }

    public function customers(Request $request, GarageCompany $company, GarageBranch $branch): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        if ($request->has('q')) {
            $customers = $this->customerService->searchCustomers($branch, $request->q);
        } else {
            $customers = $branch->customers()->with('user')->paginate(15);
        }

        return response()->json($customers);
    }
}
