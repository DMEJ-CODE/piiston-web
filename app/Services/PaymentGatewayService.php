<?php

namespace App\Services;

use App\Models\Finance\PaymentGateway;
use App\Models\Finance\PaymentTransaction;
use Illuminate\Support\Facades\Log;

class PaymentGatewayService
{
    /**
     * Process a payment through the selected gateway
     */
    public function processPayment(PaymentTransaction $transaction, PaymentGateway $gateway)
    {
        Log::info("Processing payment via {$gateway->provider} for transaction {$transaction->reference}");

        // Abstraction logic: Call provider-specific driver
        // e.g. switch($gateway->provider) { case 'STRIPE': ... }

        return [
            'success' => true,
            'gateway_reference' => 'GW-'.uniqid(),
            'status' => 'SUCCESS',
        ];
    }

    /**
     * Handle webhook/callback from provider
     */
    public function handleCallback(array $payload, $provider)
    {
        Log::info("Received callback from $provider");

        return true;
    }
}
