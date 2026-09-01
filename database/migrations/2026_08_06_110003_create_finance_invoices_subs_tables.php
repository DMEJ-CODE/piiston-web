<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 6. Invoices
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('customer_id')->constrained('users');
            $table->string('seller_type')->nullable(); // GARAGE, SELLER, PLATFORM
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->foreignId('currency_id')->constrained('currencies');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->string('status')->default('DRAFT');
            $table->dateTime('issued_at');
            $table->dateTime('due_date')->nullable();
            $table->timestamps();
        });

        // 7. Invoice Items
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->string('item_type'); // SERVICE, PART, LABOR, SUBSCRIPTION, DELIVERY
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('description');
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total', 15, 2);
            $table->timestamps();
        });

        // 9. Subscription Plans
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 15, 2);
            $table->foreignId('currency_id')->constrained('currencies');
            $table->string('duration'); // monthly, yearly
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 10. User Subscriptions
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('subscription_plans');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('status')->default('active');
            $table->boolean('auto_renew')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
        Schema::dropIfExists('subscription_plans');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
    }
};
