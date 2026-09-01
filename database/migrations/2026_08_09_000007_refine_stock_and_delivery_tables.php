<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seller_inventory', function (Blueprint $table) {
            $table->integer('reserved_quantity')->default(0)->after('quantity');
            $table->integer('sold_quantity')->default(0)->after('reserved_quantity');
            $table->integer('damaged_quantity')->default(0)->after('sold_quantity');
            $table->integer('returned_quantity')->default(0)->after('damaged_quantity');
        });

        Schema::table('product_listings', function (Blueprint $table) {
            $table->json('detailed_delivery_options')->nullable()->after('delivery_option');
            // e.g. {"pickup": true, "local": 1000, "intercity": 5000}
        });
    }

    public function down(): void
    {
        Schema::table('product_listings', function (Blueprint $table) {
            $table->dropColumn('detailed_delivery_options');
        });

        Schema::table('seller_inventory', function (Blueprint $table) {
            $table->dropColumn(['reserved_quantity', 'sold_quantity', 'damaged_quantity', 'returned_quantity']);
        });
    }
};
