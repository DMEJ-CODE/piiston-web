<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 4. Advertisements
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('media_type')->default('IMAGE'); // IMAGE, VIDEO
            $table->string('media_url')->nullable();
            $table->string('landing_type')->nullable(); // REPAIR_ORDER, PRODUCT, WEB
            $table->unsignedBigInteger('landing_id')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // 5. Ad Placements
        Schema::create('ad_placements', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Home banner, Marketplace top
            $table->string('location');
            $table->string('device_target')->default('ALL'); // MOBILE, WEB
            $table->decimal('base_price', 12, 2)->default(0);
            $table->timestamps();
        });

        // 6. Ad Creatives
        Schema::create('ad_creatives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertisement_id')->constrained('advertisements')->onDelete('cascade');
            $table->string('image_url')->nullable();
            $table->string('video_url')->nullable();
            $table->text('display_text')->nullable();
            $table->string('language_code', 5)->default('en');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_creatives');
        Schema::dropIfExists('ad_placements');
        Schema::dropIfExists('advertisements');
    }
};
