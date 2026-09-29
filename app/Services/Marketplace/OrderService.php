<?php

namespace App\Services\Marketplace;

use App\Models\Marketplace\Order;
use App\Models\Marketplace\ProductListing;
use App\Repositories\Marketplace\OrderRepositoryInterface;
use App\Services\Identity\ActivityService;
use App\Services\Notifications\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    protected $orderRepository;

    protected $activityService;

    protected $notificationService;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ActivityService $activityService,
        NotificationService $notificationService
    ) {
        $this->orderRepository = $orderRepository;
        $this->activityService = $activityService;
        $this->notificationService = $notificationService;
    }

    public function placeOrder(array $data, array $items): Order
    {
        return DB::transaction(function () use ($data, $items) {
            $listingIds = collect($items)->pluck('listing_id')->unique();
            $listings = ProductListing::query()
                ->whereIn('id', $listingIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $orderItems = collect($items)->map(function (array $item) use ($data, $listings): array {
                $listing = $listings->get($item['listing_id']);

                if (! $listing || (int) $listing->seller_id !== (int) $data['seller_id']) {
                    throw ValidationException::withMessages([
                        'items' => ['Chaque pièce doit appartenir au vendeur sélectionné.'],
                    ]);
                }

                if ((int) $listing->currency_id !== (int) $data['currency_id']) {
                    throw ValidationException::withMessages([
                        'currency_id' => ['La devise doit correspondre à celle des pièces sélectionnées.'],
                    ]);
                }

                if ($listing->quantity < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'items' => ['Une ou plusieurs pièces ne sont plus disponibles dans la quantité demandée.'],
                    ]);
                }

                $unitPrice = (float) $listing->price;

                return [
                    'listing_id' => $listing->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'subtotal' => $unitPrice * $item['quantity'],
                ];
            });

            $order = $this->orderRepository->create(array_merge($data, [
                'buyer_id' => Auth::id(),
                'payment_status' => 'PENDING',
                'order_status' => 'CREATED',
                'total_amount' => $orderItems->sum('subtotal'),
            ]));

            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            // Log activity
            $this->activityService->log(
                Auth::user(),
                'ORDER_PLACED',
                'Commande passée',
                "Votre commande de pièces détachées #{$order->id} a été enregistrée.",
                $order
            );

            // Notify Seller
            $sellerOwner = $order->seller->user;
            if ($sellerOwner) {
                $this->notificationService->send(
                    $sellerOwner,
                    'NEW_ORDER',
                    'Nouvelle commande reçue',
                    "Vous avez reçu une nouvelle commande #{$order->id} de la part de ".Auth::user()->getNameAttribute().'.',
                    ['reference_type' => 'Order', 'reference_id' => $order->id, 'category' => 'Marketplace']
                );
            }

            return $order;
        });
    }

    public function updateStatus(Order $order, string $status): void
    {
        $order->update(['order_status' => $status]);
    }
}
