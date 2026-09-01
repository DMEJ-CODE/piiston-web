<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warranty_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('garage_branches')->onDelete('cascade');
            $table->foreignId('repair_order_id')->constrained('repair_orders')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('garage_customers');
            $table->foreignId('vehicle_id')->constrained('vehicles');
            $table->string('warranty_provider')->nullable();
            $table->string('warranty_reference')->nullable();
            $table->date('warranty_expiry_date')->nullable();
            $table->text('claim_description');
            $table->string('status')->default('PENDING');
            $table->decimal('claim_amount', 12, 2)->nullable();
            $table->decimal('approved_amount', 12, 2)->nullable();
            $table->text('resolution_notes')->nullable();
            $table->dateTime('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranty_claims');
    }
};
