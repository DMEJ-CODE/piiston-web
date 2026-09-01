<?php

namespace App\Repositories\Finance;

use App\Models\Finance\PaymentTransaction;
use Illuminate\Support\Collection;

class EloquentPaymentRepository implements PaymentRepositoryInterface
{
    public function findById(int $id): ?PaymentTransaction
    {
        return PaymentTransaction::find($id);
    }

    public function findByReference(string $reference): ?PaymentTransaction
    {
        return PaymentTransaction::where('reference', $reference)->first();
    }

    public function create(array $data): PaymentTransaction
    {
        return PaymentTransaction::create($data);
    }

    public function updateStatus(int $id, string $status): bool
    {
        $transaction = $this->findById($id);
        if (! $transaction) {
            return false;
        }

        $updateData = ['status' => $status];
        if ($status === 'SUCCESS') {
            $updateData['paid_at'] = now();
        }

        return $transaction->update($updateData);
    }

    public function getUserTransactions(int $userId): Collection
    {
        return PaymentTransaction::where('payer_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
