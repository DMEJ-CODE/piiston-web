<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 12. Fuel Management
        Schema::create('fuel_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('driver_id')->constrained('drivers');
            $table->decimal('quantity_liters', 8, 2);
            $table->decimal('price_per_liter', 10, 2);
            $table->decimal('total_price', 12, 2);
            $table->string('gas_station')->nullable();
            $table->dateTime('date');
            $table->bigInteger('mileage_at_fill')->nullable();
            $table->timestamps();
        });

        // 13. Accident Management
        Schema::create('vehicle_accidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained('drivers');
            $table->string('location');
            $table->dateTime('accident_date');
            $table->text('description');
            $table->string('severity')->default('MEDIUM'); // MINOR, MEDIUM, SEVERE
            $table->decimal('repair_cost_estimated', 12, 2)->nullable();
            $table->string('status')->default('REPORTED');
            $table->timestamps();
        });

        // 14. Insurance Management
        Schema::create('vehicle_insurances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->string('provider_name');
            $table->string('policy_number');
            $table->date('start_date');
            $table->date('expiry_date');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_insurances');
        Schema::dropIfExists('vehicle_accidents');
        Schema::dropIfExists('fuel_records');
    }
};
