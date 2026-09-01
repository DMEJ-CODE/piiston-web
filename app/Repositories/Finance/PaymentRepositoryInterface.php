<?php

namespace App\Repositories\Finance;

use App\Models\Finance\PaymentTransaction;
use Illuminate\Support\Collection;

interface PaymentRepositoryInterface
{
    public function findById(int $id): ?PaymentTransaction;

    public function findByReference(string $reference): ?PaymentTransaction;

    public function create(array $data): PaymentTransaction;

    public function updateStatus(int $id, string $status): bool;

    public function getUserTransactions(int $userId): Collection;
}
