<?php

namespace Database\Seeders;

use App\Models\Finance\PaymentGateway;
use App\Models\Finance\PaymentMethod;
use App\Models\Finance\SubscriptionPlan;
use App\Models\Finance\Wallet;
use App\Models\Globalization\Country;
use App\Models\Globalization\Currency;
use App\Models\User;
use Illuminate\Database\Seeder;

class FinanceSystemSeeder extends Seeder
{
    public function run(): void
    {
        $cameroon = Country::where('iso_code', 'CM')->first();
        $nigeria = Country::where('iso_code', 'NG')->first();
        $xaf = Currency::where('code', 'XAF')->first();
        $ngn = Currency::where('code', 'NGN')->first();

        if (! $cameroon || ! $nigeria) {
            return;
        }

        // 1. Update Currencies with Country and Rate
        $xaf->update(['country_id' => $cameroon->id, 'exchange_rate' => 655.957]);
        $ngn->update(['country_id' => $nigeria->id, 'exchange_rate' => 1500.00]);

        // 2. Payment Methods
        $methods = [
            ['name' => 'Cash', 'type' => 'CASH', 'country_id' => $cameroon->id],
            ['name' => 'Orange Money', 'type' => 'MOBILE_MONEY', 'provider' => 'Orange', 'country_id' => $cameroon->id],
            ['name' => 'MTN MoMo', 'type' => 'MOBILE_MONEY', 'provider' => 'MTN', 'country_id' => $cameroon->id],
            ['name' => 'Paystack (Card)', 'type' => 'CARD', 'provider' => 'Paystack', 'country_id' => $nigeria->id],
        ];
        foreach ($methods as $m) {
            PaymentMethod::firstOrCreate(['name' => $m['name'], 'country_id' => $m['country_id']], $m);
        }

        // 3. Payment Gateways
        PaymentGateway::firstOrCreate(['provider' => 'Paystack'], [
            'name' => 'Paystack Nigeria',
            'country_id' => $nigeria->id,
            'is_active' => true,
        ]);
        PaymentGateway::firstOrCreate(['provider' => 'Flutterwave'], [
            'name' => 'Flutterwave Africa',
            'country_id' => $cameroon->id,
            'is_active' => true,
        ]);

        // 4. Subscription Plans
        $plans = [
            ['name' => 'FREE', 'price' => 0, 'currency_id' => $xaf->id, 'duration' => 'lifetime', 'description' => 'Basic access for owners.'],
            ['name' => 'PRO', 'price' => 15000, 'currency_id' => $xaf->id, 'duration' => 'monthly', 'description' => 'Advanced tools for mechanics.'],
            ['name' => 'BUSINESS', 'price' => 50000, 'currency_id' => $xaf->id, 'duration' => 'monthly', 'description' => 'Full ERP for garages.'],
        ];
        foreach ($plans as $p) {
            SubscriptionPlan::firstOrCreate(['name' => $p['name']], $p);
        }

        // 5. Wallets for test users
        $eric = User::where('email', 'eric@piiston.com')->first();
        if ($eric) {
            Wallet::firstOrCreate(
                ['user_id' => $eric->id, 'currency_id' => $xaf->id],
                ['balance' => 25000, 'status' => 'active']
            );
        }

        $sarah = User::where('email', 'seller@piiston.com')->first();
        if ($sarah) {
            Wallet::firstOrCreate(
                ['user_id' => $sarah->id, 'currency_id' => $xaf->id],
                ['balance' => 150000, 'status' => 'active']
            );
        }
    }
}
