<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garage_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('garage_companies')->onDelete('cascade');
            $table->foreignId('plan_id')->nullable()->constrained('garage_subscription_plans');
            $table->string('status')->default('active'); // active, cancelled, expired, past_due
            $table->string('billing_cycle')->default('monthly'); // monthly, yearly
            $table->dateTime('starts_at');
            $table->dateTime('ends_at')->nullable();
            $table->dateTime('trial_ends_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'plan_id', 'starts_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garage_subscriptions');
    }
};
