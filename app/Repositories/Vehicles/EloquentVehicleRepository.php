<?php

namespace App\Repositories\Vehicles;

use App\Models\Vehicles\Vehicle;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentVehicleRepository implements VehicleRepositoryInterface
{
    public function findById(int $id): ?Vehicle
    {
        return Vehicle::with(['brand', 'model', 'country', 'fuelType', 'transmission', 'health', 'images'])->find($id);
    }

    public function findByVin(string $vin): ?Vehicle
    {
        return Vehicle::where('vin', $vin)->first();
    }

    public function create(array $data): Vehicle
    {
        return Vehicle::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $vehicle = Vehicle::find($id);
        if (! $vehicle) {
            return false;
        }

        return $vehicle->update($data);
    }

    public function delete(int $id): bool
    {
        $vehicle = Vehicle::find($id);
        if (! $vehicle) {
            return false;
        }

        return $vehicle->delete();
    }

    public function getUserVehicles(int $userId): Collection
    {
        return Vehicle::where('owner_id', $userId)
            ->with(['brand', 'model', 'country', 'health', 'images'])
            ->get();
    }

    public function search(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = Vehicle::query()->with(['brand', 'model', 'owner']);

        if (isset($filters['q'])) {
            $s = $filters['q'];
            $query->where(function ($q) use ($s) {
                $q->where('vin', 'like', "%$s%")
                    ->orWhere('registration_number', 'like', "%$s%")
                    ->orWhere('license_plate', 'like', "%$s%");
            });
        }

        if (isset($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate($perPage);
    }
}
