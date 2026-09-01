<?php

namespace App\Services\Finance;

use App\Models\Finance\PaymentTransaction;
use App\Repositories\Finance\PaymentRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentService
{
    protected $paymentRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function initialize(array $data): PaymentTransaction
    {
        return $this->paymentRepository->create($data + [
            'payer_id' => Auth::id(),
            'reference' => 'PII-'.strtoupper(Str::random(10)),
            'status' => 'PENDING',
        ]);
    }

    public function handleWebhook(string $reference, string $status): void
    {
        $transaction = $this->paymentRepository->findByReference($reference);
        if ($transaction) {
            $this->paymentRepository->updateStatus($transaction->id, $status);

            if ($status === 'SUCCESS') {
                // Trigger events or secondary logic (e.g., wallet funding if applicable)
            }
        }
    }
}
