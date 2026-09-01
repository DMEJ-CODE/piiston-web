<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update Currencies (Already exists from Globalization, adding missing fields)
        Schema::table('currencies', function (Blueprint $table) {
            $table->foreignId('country_id')->nullable()->after('symbol')->constrained('countries');
            $table->decimal('exchange_rate', 15, 6)->default(1.000000)->after('country_id');
        });

        // 2. Payment Methods
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // CASH, MOBILE_MONEY, CARD, BANK_TRANSFER, WALLET
            $table->string('provider')->nullable(); // Orange, MTN, Stripe
            $table->foreignId('country_id')->constrained('countries');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 19. Payment Gateways
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('provider'); // Paystack, Flutterwave, Stripe
            $table->foreignId('country_id')->nullable()->constrained('countries');
            $table->string('api_status')->default('active');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 14. Tax Rates
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries');
            $table->string('name');
            $table->decimal('rate', 5, 2);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 15. Discount Coupons
        Schema::create('discount_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('discount_type'); // PERCENTAGE, FIXED
            $table->decimal('value', 12, 2);
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->integer('usage_limit')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_coupons');
        Schema::dropIfExists('tax_rates');
        Schema::dropIfExists('payment_gateways');
        Schema::dropIfExists('payment_methods');
        Schema::table('currencies', function (Blueprint $table) {
            $table->dropConstrainedForeignId('country_id');
            $table->dropColumn('exchange_rate');
        });
    }
};
