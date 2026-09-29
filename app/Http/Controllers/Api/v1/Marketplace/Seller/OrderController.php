<?php

namespace App\Http\Controllers\Api\v1\Marketplace\Seller;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\Order;
use App\Services\Notifications\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        if (! $seller) {
            return response()->json(['message' => 'Seller profile not found'], 404);
        }

        $orders = Order::with(['buyer', 'items.listing.part', 'address'])
            ->where('seller_id', $seller->id)
            ->latest()
            ->paginate(15);

        return response()->json($orders);
    }

    public function show(int $id): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        $order = Order::with(['buyer', 'items.listing.part', 'address', 'delivery'])
            ->where('seller_id', $seller->id)
            ->find($id);

        if (! $order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($order);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        $order = Order::where('seller_id', $seller->id)->find($id);

        if (! $order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $data = $request->validate([
            'order_status' => 'required|string|in:PENDING,CONFIRMED,PREPARING,READY,SHIPPED,DELIVERED,COMPLETED,CANCELLED',
        ]);

        $order->update(['order_status' => $data['order_status']]);

        // Notify Buyer
        $this->notificationService->send(
            $order->buyer,
            'ORDER_STATUS_UPDATED',
            'Mise à jour de commande',
            "Le statut de votre commande #{$order->id} est désormais : {$data['order_status']}.",
            ['reference_type' => 'Order', 'reference_id' => $order->id, 'category' => 'Marketplace']
        );

        // Auto-create delivery if confirmed
        if ($data['order_status'] === 'CONFIRMED' && ! $order->delivery) {
            $order->delivery()->create([
                'delivery_address' => $order->address->full_address ?? 'Pickup at Store',
                'status' => 'PREPARING',
            ]);
        }

        return response()->json($order->load('delivery'));
    }
}
