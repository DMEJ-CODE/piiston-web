<?php

namespace App\Repositories\Mechanics;

use App\Models\Mechanics\MechanicProfile;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentMechanicRepository implements MechanicRepositoryInterface
{
    public function findById(int $id): ?MechanicProfile
    {
        return MechanicProfile::with(['user', 'skills', 'certifications', 'type', 'country'])->find($id);
    }

    public function findByUserId(int $userId): ?MechanicProfile
    {
        return MechanicProfile::where('user_id', $userId)->first();
    }

    public function search(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = MechanicProfile::query()->with(['user', 'type', 'skills']);

        if (isset($filters['q'])) {
            $s = $filters['q'];
            $query->whereHas('user', function ($q) use ($s) {
                $q->where('first_name', 'like', "%$s%")
                    ->orWhere('last_name', 'like', "%$s%");
            })->orWhere('professional_title', 'like', "%$s%");
        }

        if (isset($filters['skill_id'])) {
            $query->whereHas('skills', function ($q) use ($filters) {
                $q->where('skill_id', $filters['skill_id']);
            });
        }

        if (isset($filters['country_id'])) {
            $query->where('country_id', $filters['country_id']);
        }

        return $query->paginate($perPage);
    }

    public function create(array $data): MechanicProfile
    {
        return MechanicProfile::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $profile = MechanicProfile::find($id);
        if (! $profile) {
            return false;
        }

        return $profile->update($data);
    }
}
