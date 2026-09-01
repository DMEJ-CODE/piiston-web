<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 16. Garage Inventory
        Schema::create('garage_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('garage_branches')->onDelete('cascade');
            $table->foreignId('part_id')->nullable(); // Link to Marketplace product
            $table->string('internal_part_number')->nullable();
            $table->string('name');
            $table->integer('quantity')->default(0);
            $table->integer('minimum_stock')->default(0);
            $table->integer('maximum_stock')->nullable();
            $table->string('storage_location')->nullable(); // Shelf A1, Box 2
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 18. Purchase Orders
        Schema::create('garage_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('garage_branches')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('garage_suppliers');
            $table->decimal('total_amount', 12, 2);
            $table->string('status')->default('draft');
            $table->date('order_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garage_purchase_orders');
        Schema::dropIfExists('garage_inventory');
    }
};
