<?php

namespace App\Repositories\Marketplace;

use App\Models\Marketplace\Order;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function findById(int $id): ?Order;

    public function getBuyerOrders(int $userId): LengthAwarePaginator;

    public function getSellerOrders(int $sellerId): LengthAwarePaginator;

    public function create(array $data): Order;
}
