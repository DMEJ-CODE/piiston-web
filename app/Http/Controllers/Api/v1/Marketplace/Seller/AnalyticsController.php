<?php

namespace App\Http\Controllers\Api\v1\Marketplace\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function salesSummary(Request $request): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        $period = $request->get('period', 'month');

        $query = $seller->orders()->where('payment_status', 'PAID');

        if ($period === 'week') {
            $query->where('created_at', '>=', now()->startOfWeek());
        } elseif ($period === 'month') {
            $query->where('created_at', '>=', now()->startOfMonth());
        }

        $totalRevenue = $query->sum('total_amount');
        $orderCount = $query->count();

        // Grouped by day for chart
        $chartData = $query->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as amount')
        )->groupBy('date')->orderBy('date')->get();

        return response()->json([
            'total_revenue' => $totalRevenue,
            'order_count' => $orderCount,
            'chart_data' => $chartData,
        ]);
    }

    public function stockHealth(): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        $listings = $seller->listings()->with('inventory')->get();

        $lowStockCount = $listings->filter(function ($l) {
            return $l->inventory && $l->inventory->quantity <= $l->inventory->minimum_stock;
        })->count();

        $outOfStockCount = $listings->where('quantity', 0)->count();

        return response()->json([
            'total_items' => $listings->count(),
            'low_stock' => $lowStockCount,
            'out_of_stock' => $outOfStockCount,
        ]);
    }
}
