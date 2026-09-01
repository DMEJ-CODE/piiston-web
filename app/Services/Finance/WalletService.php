<?php

namespace App\Services\Finance;

use App\Models\Finance\Wallet;
use App\Models\User;
use App\Repositories\Finance\WalletRepositoryInterface;

class WalletService
{
    protected $walletRepository;

    public function __construct(WalletRepositoryInterface $walletRepository)
    {
        $this->walletRepository = $walletRepository;
    }

    public function getWallet(User $user): Wallet
    {
        return $this->walletRepository->findByUserId($user->id) ??
               $this->walletRepository->createWallet([
                   'user_id' => $user->id,
                   'currency_id' => $user->country->currency_id ?? 1,
                   'balance' => 0,
                   'status' => 'active',
               ]);
    }

    public function deposit(User $user, float $amount, string $reference)
    {
        $wallet = $this->getWallet($user);

        return $this->walletRepository->updateBalance($wallet->id, $amount, 'DEPOSIT', $reference);
    }

    public function withdraw(User $user, float $amount, string $reference)
    {
        $wallet = $this->getWallet($user);
        if ($wallet->balance < $amount) {
            throw new \Exception('Insufficient wallet balance');
        }

        return $this->walletRepository->updateBalance($wallet->id, $amount, 'WITHDRAWAL', $reference);
    }
}
