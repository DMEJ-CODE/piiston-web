<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 9. Analytics Snapshots (Aggregation)
        Schema::create('analytics_snapshots', function (Blueprint $table) {
            $table->id();
            $table->string('snapshot_type'); // REVENUE_BY_CITY, PART_DEMAND_FORECAST
            $table->foreignId('country_id')->nullable()->constrained('countries');
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->json('data');
            $table->timestamp('generated_at')->useCurrent();
        });

        // 10. Business Insights
        Schema::create('business_insights', function (Blueprint $table) {
            $table->id();
            $table->string('owner_type');
            $table->unsignedBigInteger('owner_id');
            $table->string('title');
            $table->text('description');
            $table->string('severity')->default('INFO'); // INFO, WARNING, CRITICAL
            $table->text('recommendation')->nullable();
            $table->timestamp('generated_at')->useCurrent();
        });

        // 11. Forecasts
        Schema::create('platform_forecasts', function (Blueprint $table) {
            $table->id();
            $table->string('forecast_type'); // REVENUE, SALES, REPAIRS
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id');
            $table->decimal('predicted_value', 15, 2);
            $table->integer('confidence_score'); // 0-100
            $table->date('forecast_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_forecasts');
        Schema::dropIfExists('business_insights');
        Schema::dropIfExists('analytics_snapshots');
    }
};
