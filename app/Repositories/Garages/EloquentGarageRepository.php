<?php

namespace App\Repositories\Garages;

use App\Models\Garages\GarageBranch;
use App\Models\Garages\GarageCompany;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

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
        $query = GarageCompany::query()->with(['country', 'branches.address', 'branches.services']);

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
            $query->where('status', '!=', false);
        }

        return $query->paginate($perPage);
    }

    public function getNearby(float $latitude, float $longitude, int $radiusKm = 50): Collection
    {
        $branches = GarageBranch::where('status', '!=', false)
            ->with(['company.country', 'address', 'services'])
            ->get();

        $companiesWithoutBranches = GarageCompany::where('status', '!=', false)
            ->doesntHave('branches')
            ->with(['country'])
            ->get();

        $collection = collect();

        foreach ($branches as $branch) {
            $lat = (empty($branch->latitude) || abs((float) $branch->latitude) > 90) ? 4.0511 : (float) $branch->latitude;
            $lng = (empty($branch->longitude) || abs((float) $branch->longitude) > 180) ? 9.7679 : (float) $branch->longitude;

            $distance = 6371 * acos(
                cos(deg2rad($latitude)) * cos(deg2rad($lat)) * cos(deg2rad($lng) - deg2rad($longitude)) +
                sin(deg2rad($latitude)) * sin(deg2rad($lat))
            );

            $branch->latitude = $lat;
            $branch->longitude = $lng;
            $branch->distance = $distance;
            $collection->push($branch);
        }

        foreach ($companiesWithoutBranches as $company) {
            $pseudoBranch = new GarageBranch([
                'company_id' => $company->id,
                'name' => $company->name.' (Siège)',
                'phone' => $company->phone,
                'email' => $company->email,
                'latitude' => 4.0511,
                'longitude' => 9.7679,
                'status' => true,
            ]);
            $pseudoBranch->setRelation('company', $company);
            $pseudoBranch->distance = 5.0;
            $collection->push($pseudoBranch);
        }

        return $collection->sortBy('distance')->values();
    }

    public function create(array $data): GarageCompany
    {
        $company = GarageCompany::create($data);

        // Guarantee a primary branch is created if none exists
        if ($company->branches()->count() === 0) {
            $company->branches()->create([
                'name' => 'Siège Principal',
                'email' => $company->email,
                'phone' => $company->phone,
                'latitude' => $data['latitude'] ?? 4.0511,
                'longitude' => $data['longitude'] ?? 9.7679,
                'status' => true,
            ]);
        }

        return $company;
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
