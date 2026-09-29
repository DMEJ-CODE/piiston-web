<?php

namespace App\Http\Controllers\Api\v1\Garage;

use App\Http\Controllers\Controller;
use App\Http\Resources\Marketplace\OrderResource;
use App\Models\Garages\GarageBranch;
use App\Models\Marketplace\Order;
use Illuminate\Http\JsonResponse;

class GarageMarketplaceOrderController extends Controller
{
    public function index(GarageBranch $branch): JsonResponse
    {
        $this->authorize('viewMarketplaceOrders', $branch);

        $orders = Order::query()
            ->whereBelongsTo($branch, 'garageBranch')
            ->with([
                'buyer.country.currency',
                'buyer.roles',
                'buyer.presence',
                'currency',
                'items.listing.part',
                'seller',
            ])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return response()->json(OrderResource::collection($orders)->response()->getData(true));
    }
}
