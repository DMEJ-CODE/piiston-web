<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 3. Payment Transactions
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payer_id')->constrained('users');
            $table->foreignId('payee_id')->nullable()->constrained('users');
            $table->decimal('amount', 15, 2);
            $table->foreignId('currency_id')->constrained('currencies');
            $table->foreignId('payment_method_id')->constrained('payment_methods');
            $table->string('reference')->unique();
            $table->string('transaction_type'); // REPAIR_PAYMENT, ORDER_PAYMENT, SUBSCRIPTION, TOP_UP, WITHDRAWAL, REFUND, COMMISSION
            $table->string('status')->default('PENDING');
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();
        });

        // 11. Platform Commissions
        Schema::create('platform_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('payment_transactions')->onDelete('cascade');
            $table->decimal('commission_rate', 5, 2);
            $table->decimal('commission_amount', 15, 2);
            $table->string('status')->default('PENDING');
            $table->timestamps();
        });

        // 8. Refunds
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('payment_transactions')->onDelete('cascade');
            $table->foreignId('requested_by')->constrained('users');
            $table->text('reason')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('status')->default('PENDING');
            $table->dateTime('processed_at')->nullable();
            $table->timestamps();
        });

        // 12. Withdrawal Requests
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('wallet_id')->constrained('wallets');
            $table->decimal('amount', 15, 2);
            $table->foreignId('payment_method_id')->constrained('payment_methods');
            $table->string('status')->default('PENDING');
            $table->dateTime('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawal_requests');
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('platform_commissions');
        Schema::dropIfExists('payment_transactions');
    }
};
