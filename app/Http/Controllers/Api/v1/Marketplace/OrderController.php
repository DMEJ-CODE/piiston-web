<?php

namespace App\Http\Controllers\Api\v1\Marketplace;

use App\Http\Controllers\Controller;
use App\Http\Requests\Marketplace\StoreMarketplaceOrderRequest;
use App\Http\Resources\Marketplace\OrderResource;
use App\Models\Garages\GarageBranch;
use App\Repositories\Marketplace\OrderRepositoryInterface;
use App\Services\Finance\PaymentService;
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

    public function store(StoreMarketplaceOrderRequest $request, PaymentService $paymentService): JsonResponse
    {
        $validated = $request->validated();

        if (isset($validated['garage_branch_id'])) {
            $branch = GarageBranch::findOrFail($validated['garage_branch_id']);
            $this->authorize('operate', $branch);
        }

        $order = $this->orderService->placeOrder(
            collect($validated)->except('items')->all(),
            $validated['items'],
        );

        $data = [
            'message' => 'Order placed successfully',
            'order' => new OrderResource($order->load('items.listing.part')),
        ];

        // If payment is not CASH/LIVRAISON, initialize payment
        if ($order->payment_method !== 'CASH' && $order->payment_method !== 'LIVRAISON') {
            // Map simple strings to IDs if needed, or use payment_method_id if sent
            $methodId = $validated['payment_method_id'] ?? 1; // Fallback or lookup

            try {
                $transaction = $paymentService->initialize([
                    'amount' => $order->total_amount,
                    'payment_method_id' => $methodId,
                    'transaction_type' => 'MARKETPLACE',
                    'reference_id' => $order->id,
                ]);
                $data['checkout_url'] = $transaction->checkout_url;
                $data['payment_reference'] = $transaction->reference;
            } catch (\Exception $e) {
                // Keep order but log error
            }
        }

        return response()->json($data, 201);
    }
}
