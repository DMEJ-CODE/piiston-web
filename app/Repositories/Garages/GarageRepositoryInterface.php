<?php

namespace App\Repositories\Garages;

use App\Models\Garages\GarageCompany;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface GarageRepositoryInterface
{
    public function findById(int $id): ?GarageCompany;

    public function all(): Collection;

    public function search(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function getNearby(float $latitude, float $longitude, int $radiusKm = 10): Collection;

    public function create(array $data): GarageCompany;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
