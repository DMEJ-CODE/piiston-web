<?php

namespace App\Http\Controllers\Api\v1\Marketplace;

use App\Http\Controllers\Controller;
use App\Http\Resources\Marketplace\OrderResource;
use App\Repositories\Marketplace\OrderRepositoryInterface;
use App\Services\Marketplace\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $orderService;

    protected $orderRepository;

    public function __construct(OrderService $orderService, OrderRepositoryInterface $orderRepository)
    {
        $this->orderService = $orderService;
        $this->orderRepository = $orderRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $orders = $this->orderRepository->getBuyerOrders(Auth::id());

        return response()->json(OrderResource::collection($orders)->response()->getData(true));
    }

    public function show(int $id): JsonResponse
    {
        $order = $this->orderRepository->findById($id);
        if (! $order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $this->authorize('view', $order);

        return response()->json(new OrderResource($order));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'seller_id' => 'required|exists:seller_profiles,id',
            'address_id' => 'required|exists:addresses,id',
            'items' => 'required|array|min:1',
            'items.*.listing_id' => 'required|exists:product_listings,id',
            'items.*.quantity' => 'required|integer|min:1',
            'total_amount' => 'required|numeric',
            'currency_id' => 'required|exists:currencies,id',
        ]);

        $order = $this->orderService->placeOrder($request->except('items'), $request->items);

        return response()->json([
            'message' => 'Order placed successfully',
            'order' => new OrderResource($order->load('items.listing.part')),
        ], 201);
    }
}
