<?php

namespace App\Services\Mechanics;

use App\Models\Mechanics\MechanicAssignment;
use App\Models\Mechanics\MechanicProfile;
use App\Repositories\Mechanics\MechanicRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class MechanicService
{
    protected $mechanicRepository;

    public function __construct(MechanicRepositoryInterface $mechanicRepository)
    {
        $this->mechanicRepository = $mechanicRepository;
    }

    public function createProfile(array $data): MechanicProfile
    {
        $data['user_id'] = $data['user_id'] ?? Auth::id();
        $data['verification_status'] = 'pending';

        return $this->mechanicRepository->create($data);
    }

    public function addSkill(MechanicProfile $profile, int $skillId, array $pivotData = [])
    {
        return $profile->skills()->attach($skillId, $pivotData);
    }

    public function addCertification(MechanicProfile $profile, array $data)
    {
        return $profile->certifications()->create($data);
    }

    public function updateAvailability(MechanicProfile $profile, array $data)
    {
        return $profile->availabilities()->create($data);
    }

    public function getMyAssignments()
    {
        $profile = $this->mechanicRepository->findByUserId(Auth::id());
        if (! $profile) {
            return collect();
        }

        return MechanicAssignment::where('mechanic_id', $profile->id)
            ->with(['repairOrder.vehicle.brand', 'repairOrder.vehicle.model'])
            ->get();
    }
}
