<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 14. Geofences
        Schema::create('geofences', function (Blueprint $table) {
            $table->id();
            $table->string('owner_type'); // Fleet, Garage
            $table->unsignedBigInteger('owner_id');
            $table->string('name');
            $table->decimal('center_latitude', 10, 8);
            $table->decimal('center_longitude', 11, 8);
            $table->integer('radius_meters');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // Geofence Events
        Schema::create('geofence_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('geofence_id')->constrained('geofences')->onDelete('cascade');
            $table->string('entity_type'); // Vehicle
            $table->unsignedBigInteger('entity_id');
            $table->string('event_type'); // ENTER, EXIT
            $table->timestamp('occurred_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('geofence_events');
        Schema::dropIfExists('geofences');
    }
};
