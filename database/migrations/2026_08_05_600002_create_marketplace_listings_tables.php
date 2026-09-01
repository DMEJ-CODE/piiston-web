<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 9. Warehouses
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('seller_profiles')->onDelete('cascade');
            $table->foreignId('address_id')->nullable()->constrained('addresses');
            $table->string('name');
            $table->integer('capacity')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 7. Product Listings
        Schema::create('product_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('seller_profiles')->onDelete('cascade');
            $table->foreignId('part_id')->constrained('spare_parts')->onDelete('cascade');
            $table->decimal('price', 12, 2);
            $table->foreignId('currency_id')->constrained('currencies');
            $table->integer('quantity')->default(0);
            $table->string('condition'); // Can override part condition if needed
            $table->string('availability')->default('IN_STOCK');
            $table->string('delivery_option')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 8. Seller Inventory
        Schema::create('seller_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained('product_listings')->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->integer('minimum_stock')->default(0);
            $table->string('warehouse_location')->nullable();
            $table->timestamps();
        });

        // 20. Price History
        Schema::create('part_price_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained('product_listings')->onDelete('cascade');
            $table->decimal('old_price', 12, 2);
            $table->decimal('new_price', 12, 2);
            $table->dateTime('change_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('part_price_history');
        Schema::dropIfExists('seller_inventory');
        Schema::dropIfExists('product_listings');
        Schema::dropIfExists('warehouses');
    }
};
