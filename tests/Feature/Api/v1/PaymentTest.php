<?php

namespace Tests\Feature\Api\v1;

use App\Models\Finance\PaymentMethod;
use App\Models\Finance\PaymentTransaction;
use App\Models\Finance\Wallet;
use App\Models\Globalization\Country;
use App\Models\Globalization\Currency;
use App\Models\User;
use App\Services\Finance\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $country;

    protected $currency;

    protected $method;

    protected function setUp(): void
    {
        parent::setUp();

        $this->country = Country::create([
            'name' => 'Cameroon',
            'iso_code' => 'CM',
            'phone_code' => '+237',
            'status' => 'active',
        ]);

        $this->currency = Currency::create(['name' => 'CFA Franc', 'code' => 'XAF', 'symbol' => 'FCFA', 'status' => 'active']);

        $this->user = User::create([
            'first_name' => 'Finance',
            'last_name' => 'User',
            'email' => 'finance@example.com',
            'phone' => '123456789',
            'password' => bcrypt('password'),
            'country_id' => $this->country->id,
            'status' => 'active',
        ]);

        $this->method = PaymentMethod::create([
            'name' => 'MTN Mobile Money',
            'type' => 'MOBILE_MONEY',
            'provider' => 'MTN',
            'country_id' => $this->country->id,
            'status' => 'active',
        ]);
    }

    public function test_user_can_initialize_payment()
    {
        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/payments/pay', [
                'amount' => 5000,
                'currency_id' => $this->currency->id,
                'payment_method_id' => $this->method->id,
                'transaction_type' => 'WALLET_FUNDING',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('transaction.amount', 5000);

        $this->assertDatabaseHas('payment_transactions', ['payer_id' => $this->user->id, 'amount' => 5000]);
    }

    public function test_user_can_view_wallet_balance()
    {
        Wallet::create([
            'user_id' => $this->user->id,
            'currency_id' => $this->currency->id,
            'balance' => 1500,
            'status' => 'active',
        ]);

        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/wallet');

        $response->assertStatus(200)
            ->assertJsonPath('balance', 1500);
    }

    public function test_successful_wallet_payment_is_credited_once()
    {
        $transaction = PaymentTransaction::create([
            'payer_id' => $this->user->id,
            'amount' => 5000,
            'currency_id' => $this->currency->id,
            'payment_method_id' => $this->method->id,
            'reference' => 'PII-WALLET-TEST',
            'transaction_type' => 'WALLET_FUNDING',
            'status' => 'PENDING',
        ]);

        $service = $this->app->make(PaymentService::class);
        $service->handleWebhook($transaction->reference, 'complete');
        $service->handleWebhook($transaction->reference, 'complete');

        $this->assertDatabaseHas('payment_transactions', [
            'id' => $transaction->id,
            'status' => 'SUCCESS',
        ]);
        $this->assertDatabaseHas('wallets', [
            'user_id' => $this->user->id,
            'balance' => 5000,
        ]);
        $this->assertDatabaseCount('wallet_transactions', 1);
    }
}
