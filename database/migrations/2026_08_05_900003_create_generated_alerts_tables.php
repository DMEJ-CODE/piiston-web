<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 9. Global Alerts
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('alert_type');
            $table->string('title');
            $table->text('description');
            $table->string('priority')->default('medium');
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // 10. Automotive Maintenance Alerts
        Schema::create('maintenance_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->string('maintenance_type'); // Oil Change, Brake Check, etc.
            $table->bigInteger('current_mileage')->nullable();
            $table->bigInteger('recommended_mileage')->nullable();
            $table->date('due_date')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        // 11. Breakdown/Emergency Alerts
        Schema::create('emergency_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('location')->nullable();
            $table->string('severity')->default('HIGH');
            $table->foreignId('assigned_mechanic_id')->nullable()->constrained('users');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_alerts');
        Schema::dropIfExists('maintenance_alerts');
        Schema::dropIfExists('alerts');
    }
};
