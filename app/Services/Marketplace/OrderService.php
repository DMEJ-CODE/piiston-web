<?php

namespace App\Services\Marketplace;

use App\Models\Marketplace\Order;
use App\Repositories\Marketplace\OrderRepositoryInterface;
use App\Services\Identity\ActivityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $orderRepository;

    protected $activityService;

    public function __construct(OrderRepositoryInterface $orderRepository, ActivityService $activityService)
    {
        $this->orderRepository = $orderRepository;
        $this->activityService = $activityService;
    }

    public function placeOrder(array $data, array $items): Order
    {
        return DB::transaction(function () use ($data, $items) {
            $order = $this->orderRepository->create($data + [
                'buyer_id' => Auth::id(),
                'payment_status' => 'PENDING',
                'order_status' => 'CREATED',
            ]);

            foreach ($items as $item) {
                $order->items()->create($item);
                // Reduce stock logic would be triggered after PAID status usually
            }

            // Log activity
            $this->activityService->log(
                Auth::user(),
                'ORDER_PLACED',
                'Commande passée',
                "Votre commande de pièces détachées #{$order->id} a été enregistrée.",
                $order
            );

            return $order;
        });
    }

    public function updateStatus(Order $order, string $status): void
    {
        $order->update(['order_status' => $status]);
    }
}
