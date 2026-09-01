<?php

namespace App\Http\Controllers\Api\v1\Garage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Garage\StoreCustomerRequest;
use App\Http\Resources\Garages\CustomerResource;
use App\Models\Garages\GarageBranch;
use App\Services\Garages\CustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct(protected CustomerService $customerService) {}

    public function index(Request $request, GarageBranch $branch): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        if ($request->has('q')) {
            $customers = $this->customerService->searchCustomers($branch, $request->q);
        } else {
            $customers = $branch->customers()->with('user')->paginate(15);
        }

        return response()->json($customers);
    }

    public function store(StoreCustomerRequest $request, GarageBranch $branch): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $customer = $this->customerService->createCustomer($branch, $request->validated());

        return response()->json(new CustomerResource($customer), 201);
    }

    public function show(GarageBranch $branch, $customer): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $customer = $this->customerService->findCustomer((int) $customer);
        if (! $customer || $customer->branch_id !== $branch->id) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        return response()->json(new CustomerResource($customer));
    }

    public function update(StoreCustomerRequest $request, GarageBranch $branch, $customer): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $customer = $this->customerService->findCustomer((int) $customer);
        if (! $customer || $customer->branch_id !== $branch->id) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $this->customerService->updateCustomer($customer, $request->validated());

        return response()->json(new CustomerResource($customer));
    }

    public function destroy(GarageBranch $branch, $customer): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $customer = $this->customerService->findCustomer((int) $customer);
        if (! $customer || $customer->branch_id !== $branch->id) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $this->customerService->deleteCustomer($customer);

        return response()->json(null, 204);
    }
}
