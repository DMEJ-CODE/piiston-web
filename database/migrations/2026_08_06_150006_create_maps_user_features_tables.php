<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Location Shares
        Schema::create('location_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tracking_session_id')->nullable()->constrained('tracking_sessions')->onDelete('set null');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        // Parking Location
        Schema::create('parking_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->onDelete('set null');
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->timestamp('saved_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parking_locations');
        Schema::dropIfExists('location_shares');
    }
};
