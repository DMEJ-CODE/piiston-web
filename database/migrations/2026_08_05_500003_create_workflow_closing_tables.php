<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 12. Customer Approvals
        Schema::create('repair_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_order_id')->constrained('repair_orders')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('estimate_id')->nullable()->constrained('repair_estimates');
            $table->string('approval_status')->default('PENDING'); // PENDING, APPROVED, REJECTED
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();
        });

        // 13. Quality Control
        Schema::create('quality_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_order_id')->constrained('repair_orders')->onDelete('cascade');
            $table->foreignId('checked_by')->constrained('users');
            $table->json('checklist')->nullable();
            $table->string('result')->default('PASSED'); // PASSED, FAILED
            $table->text('notes')->nullable();
            $table->dateTime('check_date');
            $table->timestamps();
        });

        // 14. Vehicle Delivery
        Schema::create('vehicle_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_order_id')->constrained('repair_orders')->onDelete('cascade');
            $table->foreignId('delivered_by')->constrained('users');
            $table->dateTime('customer_received_at')->nullable();
            $table->dateTime('delivery_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 15. Warranty
        Schema::create('repair_warranties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_order_id')->constrained('repair_orders')->onDelete('cascade');
            $table->string('duration'); // e.g. "3 months"
            $table->date('start_date');
            $table->date('end_date');
            $table->text('conditions')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repair_warranties');
        Schema::dropIfExists('vehicle_deliveries');
        Schema::dropIfExists('quality_checks');
        Schema::dropIfExists('repair_approvals');
    }
};
