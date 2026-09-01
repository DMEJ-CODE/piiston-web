<?php

namespace App\Http\Controllers\Api\Promotions;

use App\Http\Controllers\Controller;
use App\Models\Promotions\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function validateCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $coupon = Coupon::where('code', $request->code)
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('expiration_date')->orWhere('expiration_date', '>', now());
            })
            ->first();

        if (! $coupon) {
            return response()->json(['message' => 'Invalid or expired coupon'], 404);
        }

        return response()->json($coupon);
    }
}
