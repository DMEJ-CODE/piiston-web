<?php

namespace App\Services\Finance\Providers;

use App\Models\Finance\PaymentTransaction;

interface PaymentProviderInterface
{
    public function initializePayment(PaymentTransaction $transaction): array;

    public function verifyPayment(string $reference): bool;
}
