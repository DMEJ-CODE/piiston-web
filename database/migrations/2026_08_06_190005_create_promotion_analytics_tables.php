<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 17. Promotion Analytics
        Schema::create('promotion_analytics_summary', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained('promotions')->onDelete('cascade');
            $table->integer('impressions')->default(0);
            $table->integer('clicks')->default(0);
            $table->integer('conversions')->default(0);
            $table->decimal('revenue_generated', 15, 2)->default(0);
            $table->date('stat_date');
            $table->timestamps();
        });

        // 18. Promotion Clicks
        Schema::create('promotion_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained('promotions')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('device_info')->nullable();
            $table->string('location_info')->nullable();
            $table->timestamp('clicked_at')->useCurrent();
        });

        // 19. Promotion Impressions
        Schema::create('promotion_impressions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained('promotions')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('page_context')->nullable();
            $table->timestamp('viewed_at')->useCurrent();
        });

        // 20. Promotion Conversions
        Schema::create('promotion_conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained('promotions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action_type'); // BOOKING, PURCHASE, CALL
            $table->decimal('conversion_value', 12, 2)->default(0);
            $table->timestamp('converted_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_conversions');
        Schema::dropIfExists('promotion_impressions');
        Schema::dropIfExists('promotion_clicks');
        Schema::dropIfExists('promotion_analytics_summary');
    }
};
