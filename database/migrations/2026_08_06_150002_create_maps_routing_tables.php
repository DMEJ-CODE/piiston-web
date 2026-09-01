<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 5. Routes
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('origin_location_id')->constrained('locations');
            $table->foreignId('destination_location_id')->constrained('locations');
            $table->decimal('distance_km', 10, 2)->nullable();
            $table->integer('estimated_duration_minutes')->nullable();
            $table->string('traffic_level')->nullable(); // low, medium, high
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // 6. Route Points (Polyline points)
        Schema::create('route_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained('routes')->onDelete('cascade');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->integer('sequence')->default(0);
        });

        // 7. Navigation Sessions
        Schema::create('navigation_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->onDelete('set null');
            $table->foreignId('route_id')->constrained('routes');
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });

        // 13. Travel Estimates
        Schema::create('travel_estimates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained('routes')->onDelete('cascade');
            $table->decimal('distance_km', 10, 2);
            $table->integer('estimated_time_minutes');
            $table->decimal('fuel_estimation_liters', 8, 2)->nullable();
            $table->timestamp('generated_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('travel_estimates');
        Schema::dropIfExists('navigation_sessions');
        Schema::dropIfExists('route_points');
        Schema::dropIfExists('routes');
    }
};
