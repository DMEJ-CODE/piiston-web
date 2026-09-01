<?php

namespace App\Http\Controllers\Api\v1\Marketplace\Seller;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\PartRequest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $seller = $user->sellerProfile;

        if (! $seller) {
            return response()->json(['message' => 'Seller profile not found'], 404);
        }

        $stats = [
            'total_products' => $seller->listings()->count(),
            'active_listings' => $seller->listings()->where('status', true)->count(),
            'pending_orders' => $seller->orders()->where('order_status', 'PENDING')->count(),
            'total_sales' => $seller->orders()->where('payment_status', 'PAID')->sum('total_amount'),
            'low_stock_count' => $seller->listings()->where('quantity', '<=', 5)->count(),
            'open_requests_count' => PartRequest::where('status', 'OPEN')->count(),
            'recent_orders' => $seller->orders()->with('buyer')->latest()->take(5)->get(),
        ];

        return response()->json($stats);
    }
}
