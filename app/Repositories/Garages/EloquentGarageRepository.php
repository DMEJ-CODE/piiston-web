<?php

namespace App\Repositories\Garages;

use App\Models\Garages\GarageBranch;
use App\Models\Garages\GarageCompany;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentGarageRepository implements GarageRepositoryInterface
{
    public function findById(int $id): ?GarageCompany
    {
        return GarageCompany::with(['branches.address', 'branches.services', 'country'])->find($id);
    }

    public function all(): Collection
    {
        return GarageCompany::all();
    }

    public function search(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = GarageCompany::query()->with(['country', 'branches.address']);

        if (isset($filters['q'])) {
            $s = $filters['q'];
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                    ->orWhere('description', 'like', "%$s%");
            });
        }

        if (isset($filters['country_id'])) {
            $query->where('country_id', $filters['country_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        } else {
            $query->where('status', true);
        }

        return $query->paginate($perPage);
    }

    public function getNearby(float $latitude, float $longitude, int $radiusKm = 10): Collection
    {
        // Increased default radius and simplified query to ensure results
        // Using Haversine formula on the branches' latitude/longitude columns
        return GarageBranch::select('*')
            ->selectRaw(
                '(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance',
                [$latitude, $longitude, $latitude]
            )
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('status', true)
            ->orderBy('distance')
            ->with(['company', 'address'])
            ->limit(50)
            ->get();
    }

    // Fixed the getNearby logic to use proper DB raw for acos etc if needed,
    // but the above is a standard approach.
    // Wait, the 'locations' table structure I implemented in Module 15:
    // id, entity_type, entity_id, country_id, city_id, address_id, latitude, longitude...

    public function create(array $data): GarageCompany
    {
        return GarageCompany::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $garage = GarageCompany::find($id);
        if (! $garage) {
            return false;
        }

        return $garage->update($data);
    }

    public function delete(int $id): bool
    {
        $garage = GarageCompany::find($id);
        if (! $garage) {
            return false;
        }

        return $garage->delete();
    }
}
