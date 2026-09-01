<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tracking Sessions
        Schema::create('tracking_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type'); // Mechanic, Vehicle
            $table->unsignedBigInteger('entity_id');
            $table->foreignId('started_by')->constrained('users');
            $table->string('status')->default('active');
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();

            $table->index(['entity_type', 'entity_id']);
        });

        // 11. Live Locations (Current snapshot)
        Schema::create('live_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tracking_session_id')->constrained('tracking_sessions')->onDelete('cascade');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->decimal('speed', 5, 2)->nullable();
            $table->integer('heading')->nullable();
            $table->decimal('accuracy', 8, 2)->nullable();
            $table->timestamp('captured_at')->useCurrent();
        });

        // 12. Location History
        Schema::create('location_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tracking_session_id')->constrained('tracking_sessions')->onDelete('cascade');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->decimal('speed', 5, 2)->nullable();
            $table->integer('heading')->nullable();
            $table->timestamp('recorded_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('location_histories');
        Schema::dropIfExists('live_locations');
        Schema::dropIfExists('tracking_sessions');
    }
};
