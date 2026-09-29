<?php

namespace App\Services\Mechanics;

use App\Models\Garages\GarageEmployee;
use App\Models\Garages\GarageInvitation;
use App\Models\Garages\RepairOrder;
use App\Models\Mechanics\MechanicEmployment;
use App\Models\Mechanics\MechanicProfile;
use App\Models\User;
use App\Repositories\Mechanics\MechanicRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function updateProfile(int $userId, array $data): bool
    {
        $profile = $this->mechanicRepository->findByUserId($userId);
        if (! $profile) {
            return false;
        }

        return $this->mechanicRepository->update($profile->id, $data);
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
        return RepairOrder::where('assigned_mechanic_id', Auth::id())
            ->with(['vehicle.brand', 'vehicle.model', 'garageCustomer.user', 'diagnosis', 'estimate'])
            ->latest()
            ->get();
    }

    public function getPendingInvitations(string $email)
    {
        return GarageInvitation::where('email', $email)
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->with('branch.company')
            ->get();
    }

    public function acceptInvitation(int $invitationId, int $userId): bool
    {
        $user = User::findOrFail($userId);
        $invitation = GarageInvitation::where('id', $invitationId)
            ->where('email', $user->email)
            ->whereNull('accepted_at')
            ->firstOrFail();

        $profile = $this->mechanicRepository->findByUserId($userId);
        if (! $profile) {
            throw new \Exception('Mechanic profile not found. Please create a profile first.');
        }

        return DB::transaction(function () use ($invitation, $user, $profile) {
            $invitation->update(['accepted_at' => now()]);

            // Create Garage Employee record
            GarageEmployee::create([
                'branch_id' => $invitation->branch_id,
                'user_id' => $user->id,
                'position' => 'MECHANIC',
                'hire_date' => now(),
                'status' => true,
            ]);

            // Create Mechanic Employment record
            MechanicEmployment::create([
                'mechanic_id' => $profile->id,
                'branch_id' => $invitation->branch_id,
                'position' => $invitation->role ?? 'MECHANIC',
                'start_date' => now(),
                'employment_status' => 'ACTIVE',
            ]);

            return true;
        });
    }

    public function declineInvitation(int $invitationId, int $userId): bool
    {
        $user = User::findOrFail($userId);
        $invitation = GarageInvitation::where('id', $invitationId)
            ->where('email', $user->email)
            ->whereNull('accepted_at')
            ->firstOrFail();

        return $invitation->delete();
    }
}
