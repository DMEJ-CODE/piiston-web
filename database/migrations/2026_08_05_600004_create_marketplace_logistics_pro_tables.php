<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 10. Stock Movements
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->foreignId('listing_id')->constrained('product_listings')->onDelete('cascade');
            $table->string('type'); // PURCHASE, SALE, RETURN, ADJUSTMENT
            $table->integer('quantity');
            $table->string('reason')->nullable();
            $table->dateTime('movement_date');
            $table->timestamps();
        });

        // 15. Deliveries
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('delivery_provider')->nullable();
            $table->string('tracking_number')->nullable();
            $table->text('delivery_address');
            $table->string('status')->default('PREPARING');
            $table->dateTime('estimated_date')->nullable();
            $table->dateTime('delivered_date')->nullable();
            $table->timestamps();
        });

        // 16. Seller Reviews
        Schema::create('seller_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('seller_profiles')->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        // 17. Part Requests
        Schema::create('part_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->onDelete('set null');
            $table->text('description');
            $table->string('photo')->nullable();
            $table->integer('quantity')->default(1);
            $table->string('status')->default('OPEN');
            $table->timestamps();
        });

        // 18. Mechanic Recommendations
        Schema::create('part_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mechanic_id')->constrained('mechanic_profiles')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('part_id')->constrained('spare_parts')->onDelete('cascade');
            $table->foreignId('repair_order_id')->nullable()->constrained('repair_orders')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->string('status')->default('PENDING');
            $table->timestamps();
        });

        // 19. Bulk Orders
        Schema::create('bulk_orders', function (Blueprint $table) {
            $table->id();
            $table->string('buyer_type'); // GARAGE, FLEET, BUSINESS
            $table->unsignedBigInteger('buyer_id');
            $table->foreignId('seller_id')->constrained('seller_profiles')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('discount', 5, 2)->default(0);
            $table->string('status')->default('PENDING');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bulk_orders');
        Schema::dropIfExists('part_recommendations');
        Schema::dropIfExists('part_requests');
        Schema::dropIfExists('seller_reviews');
        Schema::dropIfExists('deliveries');
        Schema::dropIfExists('stock_movements');
    }
};
