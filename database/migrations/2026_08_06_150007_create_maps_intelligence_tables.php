<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Traffic Snapshot
        Schema::create('traffic_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->nullable()->constrained('regions');
            $table->foreignId('provider_id')->constrained('map_providers')->onDelete('cascade');
            $table->string('traffic_level'); // LOW, MEDIUM, HIGH, CRITICAL
            $table->timestamp('captured_at')->useCurrent();
        });

        // Nearby Search History
        Schema::create('nearby_searches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('search_type'); // Garage, Mechanic, Fuel, etc.
            $table->integer('radius_meters');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nearby_searches');
        Schema::dropIfExists('traffic_snapshots');
    }
};
