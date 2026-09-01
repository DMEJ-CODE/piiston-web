<?php

namespace App\Services\Garages;

use App\Models\Garages\AuditLog;
use App\Models\Garages\GarageBranch;
use App\Models\Garages\RepairPart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function createInventoryItem(GarageBranch $branch, array $data): RepairPart
    {
        return RepairPart::create(array_merge($data, ['branch_id' => $branch->id]));
    }

    public function updateStock(RepairPart $item, int $quantityChange): bool
    {
        $newQuantity = $item->stock_quantity + $quantityChange;
        if ($newQuantity < 0) {
            return false;
        }

        return $item->update(['stock_quantity' => $newQuantity]);
    }

    public function getLowStockItems(GarageBranch $branch)
    {
        return RepairPart::where('branch_id', $branch->id)
            ->whereColumn('stock_quantity', '<=', 'minimum_stock')
            ->where('is_active', true)
            ->get();
    }

    public function adjustStock(RepairPart $item, int $quantity, ?string $reason = null): bool
    {
        return DB::transaction(function () use ($item, $quantity, $reason) {
            $oldQuantity = $item->stock_quantity;
            $newQuantity = $oldQuantity + $quantity;

            if ($newQuantity < 0) {
                return false;
            }

            $item->update(['stock_quantity' => $newQuantity]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'entity_type' => RepairPart::class,
                'entity_id' => $item->id,
                'action' => 'updated',
                'old_values' => ['stock_quantity' => $oldQuantity],
                'new_values' => ['stock_quantity' => $newQuantity, 'reason' => $reason],
            ]);

            return true;
        });
    }

    public function getBranchInventory(GarageBranch $branch, int $perPage = 15)
    {
        return RepairPart::where('branch_id', $branch->id)
            ->paginate($perPage);
    }
}
