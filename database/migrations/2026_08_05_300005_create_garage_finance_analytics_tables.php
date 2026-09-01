<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 21. Invoices
        Schema::create('garage_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_order_id')->constrained('repair_orders')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('garage_customers');
            $table->decimal('amount', 12, 2);
            $table->decimal('tax', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total_payable', 12, 2);
            $table->string('status')->default('unpaid');
            $table->timestamps();
        });

        // 21b. Payments
        Schema::create('garage_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('garage_invoices')->onDelete('cascade');
            $table->string('payment_method');
            $table->decimal('amount', 12, 2);
            $table->string('transaction_reference')->nullable();
            $table->string('status')->default('completed');
            $table->dateTime('payment_date');
            $table->timestamps();
        });

        // 22. Reviews
        Schema::create('garage_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('garage_companies')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('garage_customers');
            $table->integer('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->dateTime('review_date');
            $table->timestamps();
        });

        // 23. Statistics (Caching table)
        Schema::create('garage_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('garage_branches')->onDelete('cascade');
            $table->string('period'); // daily, monthly, yearly
            $table->integer('total_clients')->default(0);
            $table->integer('total_repairs')->default(0);
            $table->decimal('revenue', 15, 2)->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garage_statistics');
        Schema::dropIfExists('garage_reviews');
        Schema::dropIfExists('garage_payments');
        Schema::dropIfExists('garage_invoices');
    }
};
