<?php

namespace App\Policies;

use App\Models\Marketplace\Order;
use App\Models\Marketplace\SellerProfile;
use App\Models\User;

class MarketplacePolicy
{
    public function manageStore(User $user, SellerProfile $store): bool
    {
        return $user->id === $store->user_id || $user->hasRole('ADMIN');
    }

    public function viewOrder(User $user, Order $order): bool
    {
        return $user->id === $order->buyer_id
            || $user->id === $order->seller->user_id
            || $user->hasRole('ADMIN');
    }
}
