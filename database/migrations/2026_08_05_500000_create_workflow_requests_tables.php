<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Service Requests
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->string('request_type'); // REPAIR, MAINTENANCE, DIAGNOSIS, EMERGENCY, etc.
            $table->text('description')->nullable();
            $table->string('priority')->default('normal');
            $table->string('location')->nullable();
            $table->string('status')->default('PENDING');
            $table->timestamps();
        });

        // 16. Emergency Requests
        Schema::create('emergency_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->string('location');
            $table->text('problem_description');
            $table->foreignId('assigned_mechanic_id')->nullable()->constrained('users');
            $table->string('status')->default('REQUESTED');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_requests');
        Schema::dropIfExists('service_requests');
    }
};
