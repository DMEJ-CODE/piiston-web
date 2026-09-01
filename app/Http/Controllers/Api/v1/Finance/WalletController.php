<?php

namespace App\Http\Controllers\Api\v1\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\WithdrawWalletRequest;
use App\Http\Resources\Finance\WalletResource;
use App\Services\Finance\WalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function show(): JsonResponse
    {
        $wallet = $this->walletService->getWallet(Auth::user());

        return response()->json(new WalletResource($wallet));
    }

    public function history(): JsonResponse
    {
        $wallet = $this->walletService->getWallet(Auth::user());

        return response()->json($wallet->transactions()->orderBy('created_at', 'desc')->paginate(20));
    }

    public function withdraw(WithdrawWalletRequest $request): JsonResponse
    {
        try {
            $this->walletService->withdraw(Auth::user(), $request->amount, 'Manual Withdrawal');

            return response()->json(['message' => 'Withdrawal successful']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
