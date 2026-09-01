<?php

namespace App\Services\Garages;

use App\Models\Garages\GarageBranch;
use App\Models\Garages\GaragePurchaseOrder;
use App\Models\Garages\GarageSupplier;
use App\Models\Garages\PurchaseOrderItem;
use App\Models\Garages\RepairPart;
use Illuminate\Support\Facades\DB;

class PurchaseOrderService
{
    public function createPurchaseOrder(GarageBranch $branch, GarageSupplier $supplier, array $data): GaragePurchaseOrder
    {
        return DB::transaction(function () use ($branch, $supplier, $data) {
            $po = GaragePurchaseOrder::create([
                'branch_id' => $branch->id,
                'supplier_id' => $supplier->id,
                'total_amount' => 0,
                'status' => 'draft',
                'order_date' => now()->toDateString(),
            ]);

            $total = 0;
            foreach ($data['items'] as $item) {
                $itemTotal = $item['quantity'] * $item['unit_price'];
                $po->items()->create([
                    'part_id' => $item['part_id'] ?? null,
                    'part_name' => $item['part_name'],
                    'part_number' => $item['part_number'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $itemTotal,
                ]);
                $total += $itemTotal;
            }

            $po->update(['total_amount' => $total]);

            return $po;
        });
    }

    public function updateStatus(GaragePurchaseOrder $purchaseOrder, string $status): bool
    {
        return $purchaseOrder->update(['status' => $status]);
    }

    public function receiveItems(GaragePurchaseOrder $purchaseOrder, array $receivedItems): bool
    {
        return DB::transaction(function () use ($purchaseOrder, $receivedItems) {
            foreach ($receivedItems as $itemId => $receivedQty) {
                $item = PurchaseOrderItem::where('purchase_order_id', $purchaseOrder->id)
                    ->where('id', $itemId)
                    ->first();

                if ($item) {
                    $item->update(['received_quantity' => $receivedQty]);

                    if ($item->part_id) {
                        $part = RepairPart::find($item->part_id);
                        if ($part) {
                            $part->increment('stock_quantity', $receivedQty);
                        }
                    }
                }
            }

            $allReceived = $purchaseOrder->items()->every(function ($item) {
                return $item->received_quantity >= $item->quantity;
            });

            if ($allReceived) {
                $purchaseOrder->update(['status' => 'received']);
            }

            return true;
        });
    }

    public function getBranchPurchaseOrders(GarageBranch $branch, int $perPage = 15)
    {
        return GaragePurchaseOrder::where('branch_id', $branch->id)
            ->with(['supplier', 'items'])
            ->paginate($perPage);
    }
}
