<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 10. Coupons
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained('promotions')->onDelete('cascade');
            $table->string('code')->unique();
            $table->string('discount_type'); // PERCENTAGE, FIXED
            $table->decimal('value', 12, 2);
            $table->integer('usage_limit')->nullable();
            $table->dateTime('expiration_date')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // 11. Discount Rules
        Schema::create('discount_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained('promotions')->onDelete('cascade');
            $table->text('condition');
            $table->decimal('discount_value', 12, 2);
            $table->decimal('maximum_discount', 12, 2)->nullable();
            $table->timestamps();
        });

        // 12. Partner Offers
        Schema::create('partner_offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partner_id'); // Future Partner module
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('discount_label')->nullable();
            $table->dateTime('valid_until')->nullable();
            $table->timestamps();
        });

        // 13. Sponsored Listings
        Schema::create('sponsored_listings', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type'); // Garage, SparePart, Mechanic
            $table->unsignedBigInteger('entity_id');
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->integer('priority')->default(1);
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->timestamps();

            $table->index(['entity_type', 'entity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sponsored_listings');
        Schema::dropIfExists('partner_offers');
        Schema::dropIfExists('discount_rules');
        Schema::dropIfExists('coupons');
    }
};
