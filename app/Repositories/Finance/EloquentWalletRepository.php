<?php

namespace App\Repositories\Finance;

use App\Models\Finance\Wallet;
use App\Models\Finance\WalletTransaction;
use Illuminate\Support\Facades\DB;

class EloquentWalletRepository implements WalletRepositoryInterface
{
    public function findByUserId(int $userId): ?Wallet
    {
        return Wallet::where('user_id', $userId)->first();
    }

    public function createWallet(array $data): Wallet
    {
        return Wallet::create($data);
    }

    public function updateBalance(int $walletId, float $amount, string $type, string $reference): WalletTransaction
    {
        return DB::transaction(function () use ($walletId, $amount, $type, $reference) {
            $wallet = Wallet::lockForUpdate()->find($walletId);

            $balanceBefore = $wallet->balance;
            $balanceAfter = $type === 'DEPOSIT' ? $balanceBefore + $amount : $balanceBefore - $amount;

            $wallet->update(['balance' => $balanceAfter]);

            return $wallet->transactions()->create([
                'transaction_type' => $type,
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reference' => $reference,
            ]);
        });
    }
}
