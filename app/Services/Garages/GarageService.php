<?php

namespace App\Services\Garages;

use App\Models\Garages\GarageBranch;
use App\Models\Garages\GarageCompany;
use App\Repositories\Garages\GarageRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class GarageService
{
    protected $garageRepository;

    public function __construct(GarageRepositoryInterface $garageRepository)
    {
        $this->garageRepository = $garageRepository;
    }

    public function createGarage(array $data): GarageCompany
    {
        $data['owner_id'] = $data['owner_id'] ?? Auth::id();
        $data['status'] = $data['status'] ?? true;

        return $this->garageRepository->create($data);
    }

    public function addBranch(GarageCompany $company, array $data): GarageBranch
    {
        return $company->branches()->create($data);
    }

    public function addService(GarageBranch $branch, array $data)
    {
        return $branch->services()->create($data);
    }

    public function hireEmployee(GarageBranch $branch, int $userId, array $data)
    {
        return $branch->employees()->create($data + ['user_id' => $userId]);
    }
}
