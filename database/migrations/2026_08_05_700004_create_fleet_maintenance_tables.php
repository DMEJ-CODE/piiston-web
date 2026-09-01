<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 9. Maintenance Plans (B2B Rules)
        Schema::create('maintenance_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fleet_id')->constrained('fleets')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('interval_type'); // TIME_BASED, MILEAGE_BASED
            $table->integer('interval_value'); // e.g. 5000 (km) or 180 (days)
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 10. Maintenance Schedules (Automation)
        Schema::create('fleet_maintenance_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('maintenance_plans')->onDelete('cascade');
            $table->date('next_due_date')->nullable();
            $table->bigInteger('next_due_mileage')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // 11. Fleet Repairs (Financial summary)
        Schema::create('fleet_repairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fleet_id')->constrained('fleets')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('repair_order_id')->constrained('repair_orders');
            $table->decimal('cost', 12, 2);
            $table->dateTime('date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fleet_repairs');
        Schema::dropIfExists('fleet_maintenance_schedules');
        Schema::dropIfExists('maintenance_plans');
    }
};
