<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_listings', function (Blueprint $table) {
            $table->decimal('professional_price', 12, 2)->nullable()->after('price');
            $table->string('sku')->nullable()->after('part_id');
        });

        Schema::table('spare_parts', function (Blueprint $table) {
            $table->string('oem_reference')->nullable()->after('part_number');
        });

        Schema::table('seller_profiles', function (Blueprint $table) {
            $table->json('business_hours')->nullable()->after('description');
            $table->json('delivery_terms')->nullable()->after('business_hours');
        });
    }

    public function down(): void
    {
        Schema::table('seller_profiles', function (Blueprint $table) {
            $table->dropColumn(['business_hours', 'delivery_terms']);
        });

        Schema::table('spare_parts', function (Blueprint $table) {
            $table->dropColumn('oem_reference');
        });

        Schema::table('product_listings', function (Blueprint $table) {
            $table->dropColumn(['professional_price', 'sku']);
        });
    }
};
