<?php

namespace App\Http\Controllers\Api\v1\Marketplace\Seller;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\Delivery;
use App\Models\Marketplace\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    public function index(): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        $deliveries = Delivery::whereHas('order', function ($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->with('order.buyer')->latest()->get();

        return response()->json($deliveries);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        $delivery = Delivery::whereHas('order', function ($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->findOrFail($id);

        $data = $request->validate([
            'status' => 'required|string|in:PREPARING,SHIPPED,IN_TRANSIT,DELIVERED,CANCELLED',
            'tracking_number' => 'nullable|string',
            'delivery_provider' => 'nullable|string',
            'estimated_date' => 'nullable|date',
        ]);

        $delivery->update($data);

        // Sync order status if delivered
        if ($data['status'] === 'DELIVERED') {
            $delivery->order->update(['order_status' => 'DELIVERED']);
            $delivery->update(['delivered_date' => now()]);
        }

        return response()->json($delivery);
    }
}
