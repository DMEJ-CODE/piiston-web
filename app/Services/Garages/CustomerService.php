<?php

namespace App\Services\Garages;

use App\Models\Garages\GarageBranch;
use App\Models\Garages\GarageCustomer;

class CustomerService
{
    public function createCustomer(GarageBranch $branch, array $data): GarageCustomer
    {
        return $branch->customers()->create($data);
    }

    public function findCustomer(int $id): ?GarageCustomer
    {
        return GarageCustomer::with(['user', 'vehicles'])->find($id);
    }

    public function updateCustomer(GarageCustomer $customer, array $data): bool
    {
        return $customer->update($data);
    }

    public function deleteCustomer(GarageCustomer $customer): bool
    {
        return $customer->delete();
    }

    public function getCustomerVehicles(GarageCustomer $customer)
    {
        if ($customer->user_id) {
            return $customer->user->vehicles ?? collect();
        }

        return collect();
    }

    public function searchCustomers(GarageBranch $branch, string $query, int $perPage = 15)
    {
        return GarageCustomer::where('branch_id', $branch->id)
            ->where(function ($q) use ($query) {
                $q->whereHas('user', function ($uq) use ($query) {
                    $uq->where('first_name', 'like', "%$query%")
                        ->orWhere('last_name', 'like', "%$query%")
                        ->orWhere('email', 'like', "%$query%");
                })->orWhere('customer_type', 'like', "%$query%");
            })
            ->paginate($perPage);
    }
}
