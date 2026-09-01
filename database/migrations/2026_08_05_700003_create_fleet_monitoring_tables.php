<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 7. Vehicle Usage Logs (Missions)
        Schema::create('vehicle_usage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('driver_id')->constrained('drivers');
            $table->string('start_location')->nullable();
            $table->string('end_location')->nullable();
            $table->decimal('distance_km', 10, 2)->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->text('purpose')->nullable();
            $table->timestamps();
        });

        // 8. GPS Tracking (Real-time storage)
        Schema::create('vehicle_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->decimal('speed', 5, 2)->nullable();
            $table->integer('direction')->nullable();
            $table->timestamp('tracked_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_tracking');
        Schema::dropIfExists('vehicle_usage_logs');
    }
};
