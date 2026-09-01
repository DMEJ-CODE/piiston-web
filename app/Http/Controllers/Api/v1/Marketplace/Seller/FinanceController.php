<?php

namespace App\Http\Controllers\Api\v1\Marketplace\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    public function walletSummary(): JsonResponse
    {
        $user = Auth::user();
        $wallet = $user->wallet()->firstOrCreate(
            ['currency_id' => 1], // Default currency
            ['balance' => 0, 'status' => 'active']
        );

        $transactions = $wallet->transactions()->latest()->take(10)->get();

        return response()->json([
            'balance' => $wallet->balance,
            'currency' => $wallet->currency,
            'recent_transactions' => $transactions,
            'pending_earnings' => $user->sellerProfile->orders()->where('payment_status', 'PAID')->where('order_status', '!=', 'DELIVERED')->sum('total_amount'),
        ]);
    }

    public function transactions(Request $request): JsonResponse
    {
        $wallet = Auth::user()->wallet;
        if (! $wallet) {
            return response()->json([]);
        }

        return response()->json($wallet->transactions()->latest()->paginate(20));
    }

    public function updatePayoutInfo(Request $request): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        $data = $request->validate([
            'payout_method' => 'required|string',
            'bank_account_info' => 'required|string',
        ]);

        $seller->update($data);

        return response()->json(['message' => 'Payout information updated']);
    }
}
