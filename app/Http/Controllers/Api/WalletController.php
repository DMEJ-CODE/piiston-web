<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function balance()
    {
        return response()->json(
            Auth::user()->wallet()->with('currency')->first()
        );
    }

    public function transactions()
    {
        $wallet = Auth::user()->wallet;
        if (! $wallet) {
            return response()->json([], 404);
        }

        return response()->json(
            $wallet->transactions()->orderBy('created_at', 'desc')->paginate(30)
        );
    }
}
