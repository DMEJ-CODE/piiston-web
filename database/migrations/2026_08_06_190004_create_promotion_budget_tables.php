<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 14. Promotion Budgets
        Schema::create('promotion_budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->decimal('total_budget', 15, 2);
            $table->decimal('spent_amount', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2);
            $table->timestamps();
        });

        // 15. Promotion Payments
        Schema::create('promotion_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->unsignedBigInteger('payment_transaction_id'); // Link to Finance module
            $table->decimal('amount', 15, 2);
            $table->string('status')->default('completed');
            $table->timestamps();
        });

        // 16. Promotion Approvals
        Schema::create('promotion_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('promotion_id');
            $table->foreignId('approved_by')->constrained('users');
            $table->string('status')->default('PENDING'); // APPROVED, REJECTED
            $table->text('reason')->nullable();
            $table->dateTime('approval_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_approvals');
        Schema::dropIfExists('promotion_payments');
        Schema::dropIfExists('promotion_budgets');
    }
};
