<?php

namespace App\Repositories\Marketplace;

use App\Models\Marketplace\Order;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentOrderRepository implements OrderRepositoryInterface
{
    public function findById(int $id): ?Order
    {
        return Order::with(['items.listing.part', 'seller', 'buyer', 'currency'])->find($id);
    }

    public function getBuyerOrders(int $userId): LengthAwarePaginator
    {
        return Order::where('buyer_id', $userId)
            ->with(['currency', 'items.listing.part', 'seller'])
            ->latest()
            ->paginate(15);
    }

    public function getSellerOrders(int $sellerId): LengthAwarePaginator
    {
        return Order::where('seller_id', $sellerId)
            ->with(['buyer'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }

    public function create(array $data): Order
    {
        return Order::create($data);
    }
}
