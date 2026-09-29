<?php

namespace App\Services\Finance;

use App\Models\Finance\PaymentTransaction;
use App\Repositories\Finance\PaymentRepositoryInterface;
use App\Services\Payments\NotchPayClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class PaymentService
{
    public function __construct(
        protected PaymentRepositoryInterface $paymentRepository,
        protected NotchPayClient $notchPay,
        protected ?WalletService $walletService = null,
    ) {}

    public function initialize(array $data): PaymentTransaction
    {
        $transaction = $this->paymentRepository->create($data + [
            'payer_id' => Auth::id(),
            'reference' => 'PII-'.strtoupper(Str::random(10)),
            'status' => 'PENDING',
        ]);

        $user = Auth::user();
        $currency = $transaction->currency;
        if (! config('services.notchpay.key')) {
            return $transaction;
        }

        try {
            $response = $this->notchPay->initialize([
                'amount' => (float) $transaction->amount,
                'currency' => $currency?->code ?? 'XAF',
                'email' => $user->email,
                'phone' => $user->phone,
                'description' => 'Piiston '.$transaction->transaction_type.' payment',
                'reference' => $transaction->reference,
                'callback' => config('services.notchpay.payment_callback_url') ?: url('/api/payments/notchpay/callback'),
                'locked_currency' => $currency?->code ?? 'XAF',
                'locked_country' => 'CM',
                'customer_meta' => ['transaction_id' => $transaction->id, 'user_id' => $user->id],
                'phone' => $data['phone'] ?? $user->phone,
                'channel' => $data['channel'] ?? null,
            ]);
        } catch (Throwable $exception) {
            if (app()->environment(['local', 'testing'])) {
                report($exception);

                return $transaction;
            }

            throw $exception;
        }
        $transaction->update([
            'notchpay_reference' => data_get($response, 'transaction.reference', $transaction->reference),
            'checkout_url' => data_get($response, 'authorization_url'),
        ]);

        return $transaction->fresh();
    }

    public function handleWebhook(string $reference, string $status): void
    {
        $transaction = $this->paymentRepository->findByReference($reference)
            ?? PaymentTransaction::where('notchpay_reference', $reference)->first();

        if ($transaction) {
            $normalizedStatus = $this->normalizeStatus($status);

            if ($transaction->status === 'SUCCESS') {
                return;
            }

            DB::transaction(function () use ($transaction, $normalizedStatus): void {
                $this->paymentRepository->updateStatus($transaction->id, $normalizedStatus);

                if ($normalizedStatus === 'SUCCESS' && $transaction->transaction_type === 'WALLET_FUNDING') {
                    $alreadyCredited = $transaction->payer?->wallet?->transactions()
                        ->where('reference', $transaction->reference)
                        ->exists();

                    if (! $alreadyCredited && $transaction->payer) {
                        ($this->walletService ?? app(WalletService::class))->deposit(
                            $transaction->payer,
                            (float) $transaction->amount,
                            $transaction->reference,
                        );
                    }
                }
            });
        }
    }

    private function normalizeStatus(string $status): string
    {
        return match (strtolower(trim($status))) {
            'complete', 'completed', 'success', 'successful', 'paid' => 'SUCCESS',
            'failed', 'failure', 'canceled', 'cancelled', 'expired' => 'FAILED',
            default => 'PENDING',
        };
    }
}
