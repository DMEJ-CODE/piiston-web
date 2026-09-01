<?php

namespace App\Repositories\Workflows;

use App\Models\Garages\RepairOrder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentRepairRepository implements RepairRepositoryInterface
{
    public function findById(int $id): ?RepairOrder
    {
        return RepairOrder::with(['vehicle', 'garageCustomer.user', 'mechanic', 'diagnosis', 'estimate', 'tasks', 'parts', 'progress'])->find($id);
    }

    public function getUserRepairs(int $userId): Collection
    {
        return RepairOrder::whereHas('garageCustomer', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->with(['vehicle', 'branch', 'diagnosis', 'estimate'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getGarageRepairs(int $branchId): LengthAwarePaginator
    {
        return RepairOrder::where('branch_id', $branchId)
            ->with(['vehicle', 'garageCustomer.user', 'mechanic'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }

    public function search(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = RepairOrder::query()->with(['vehicle', 'garageCustomer.user', 'branch']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (isset($filters['user_id'])) {
            $query->whereHas('garageCustomer', function ($q) use ($filters) {
                $q->where('user_id', $filters['user_id']);
            });
        }

        if (isset($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }

        return $query->orderBy('updated_at', 'desc')->paginate($perPage);
    }

    public function create(array $data): RepairOrder
    {
        return RepairOrder::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $repair = RepairOrder::find($id);
        if (! $repair) {
            return false;
        }

        return $repair->update($data);
    }

    public function getHistory(int $repairId): Collection
    {
        $repair = RepairOrder::find($repairId);
        if (! $repair) {
            return collect();
        }

        return $repair->progress;
    }
}
