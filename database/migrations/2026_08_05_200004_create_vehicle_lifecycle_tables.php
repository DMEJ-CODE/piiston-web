<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 11. Maintenance
        Schema::create('vehicle_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->string('service_name');
            $table->foreignId('garage_id')->nullable(); // Will link to Garages module later
            $table->date('date');
            $table->bigInteger('mileage')->nullable();
            $table->decimal('cost', 12, 2)->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('completed');
            $table->timestamps();
        });

        // 12. History
        Schema::create('vehicle_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->string('event_type'); // PURCHASE, REPAIR, ACCIDENT, etc.
            $table->text('description')->nullable();
            $table->dateTime('date');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // 13. Ownership History
        Schema::create('vehicle_ownerships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('owner_id')->constrained('users');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
        });

        // 14. Health Status
        Schema::create('vehicle_healths', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->integer('health_score')->default(100);
            $table->dateTime('last_check')->nullable();
            $table->string('risk_level')->default('low'); // low, medium, high
            $table->text('recommendation')->nullable();
            $table->timestamps();
        });

        // 15. Alerts
        Schema::create('vehicle_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->string('type'); // Oil Change, Insurance Expiry, etc.
            $table->text('message');
            $table->string('priority')->default('medium');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_alerts');
        Schema::dropIfExists('vehicle_healths');
        Schema::dropIfExists('vehicle_ownerships');
        Schema::dropIfExists('vehicle_histories');
        Schema::dropIfExists('vehicle_maintenances');
    }
};
