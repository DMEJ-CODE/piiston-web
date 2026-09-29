<?php

use App\Models\Finance\SubscriptionPlan;
use App\Models\Globalization\Currency;

it('loads the public pricing page', function () {
    Currency::query()->create([
        'name' => 'Central African Franc',
        'code' => 'XAF',
        'symbol' => 'FCFA',
        'decimal_places' => 0,
        'status' => true,
    ]);

    SubscriptionPlan::query()->create([
        'name' => 'Starter',
        'price' => 25000,
        'currency_id' => Currency::query()->value('id'),
        'duration' => 'month',
        'description' => 'For solo workshops and small operators.',
        'features' => ['job_cards', 'customers', 'inventory'],
        'status' => true,
    ]);

    $this->get(route('pricing.index'))
        ->assertOk()
        ->assertSee('Choose the plan that fits your business');
});

it('redirects guest users to login before checkout', function () {
    $currency = Currency::query()->create([
        'name' => 'Central African Franc',
        'code' => 'XAF',
        'symbol' => 'FCFA',
        'decimal_places' => 0,
        'status' => true,
    ]);

    $plan = SubscriptionPlan::query()->create([
        'name' => 'Growth',
        'price' => 60000,
        'currency_id' => $currency->id,
        'duration' => 'month',
        'description' => 'For active teams and growing garages.',
        'features' => ['job_cards', 'inventory', 'reports'],
        'status' => true,
    ]);

    $this->post(route('pricing.checkout', $plan))
        ->assertRedirect(route('login'));
});
