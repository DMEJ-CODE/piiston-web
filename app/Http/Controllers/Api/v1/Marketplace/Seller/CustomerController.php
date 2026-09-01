<?php

namespace App\Http\Controllers\Api\v1\Marketplace\Seller;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index(): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;

        // Get unique customers who have ordered from this seller
        $customers = User::whereHas('invoices', function ($q) use ($seller) {
            $q->whereHas('order', function ($o) use ($seller) {
                $o->where('seller_id', $seller->id);
            });
        })->distinct()->get();

        return response()->json($customers);
    }
}
