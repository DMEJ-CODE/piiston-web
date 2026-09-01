<?php

namespace App\Repositories\Finance;

use App\Models\Finance\Wallet;
use App\Models\Finance\WalletTransaction;

interface WalletRepositoryInterface
{
    public function findByUserId(int $userId): ?Wallet;

    public function createWallet(array $data): Wallet;

    public function updateBalance(int $walletId, float $amount, string $type, string $reference): WalletTransaction;
}
