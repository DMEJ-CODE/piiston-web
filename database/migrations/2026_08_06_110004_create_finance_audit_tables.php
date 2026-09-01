<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 16. Business Expenses
        Schema::create('business_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('business_type'); // GARAGE, SELLER
            $table->unsignedBigInteger('business_id');
            $table->string('category');
            $table->decimal('amount', 15, 2);
            $table->foreignId('currency_id')->constrained('currencies');
            $table->text('description')->nullable();
            $table->date('date');
            $table->timestamps();
        });

        // 17. Platform Revenue
        Schema::create('platform_revenue', function (Blueprint $table) {
            $table->id();
            $table->string('source_type'); // MARKETPLACE, SUBSCRIPTION, COMMISSION, ADVERTISING
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->decimal('amount', 15, 2);
            $table->foreignId('currency_id')->constrained('currencies');
            $table->timestamps();
        });

        // 18. Financial Reports
        Schema::create('financial_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('period'); // daily, monthly, yearly
            $table->decimal('income', 15, 2);
            $table->decimal('expenses', 15, 2);
            $table->decimal('profit', 15, 2);
            $table->foreignId('currency_id')->constrained('currencies');
            $table->dateTime('generated_at');
            $table->timestamps();
        });

        // 20. Audit Logs
        Schema::create('financial_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->nullable()->constrained('payment_transactions');
            $table->string('action');
            $table->foreignId('performed_by')->constrained('users');
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_audit_logs');
        Schema::dropIfExists('financial_reports');
        Schema::dropIfExists('platform_revenue');
        Schema::dropIfExists('business_expenses');
    }
};
