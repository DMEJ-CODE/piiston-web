<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Categories
        Schema::create('vehicle_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Car, Motorcycle, Truck, etc.
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // 2. Fuel Types
        Schema::create('fuel_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Petrol, Diesel, etc.
            $table->timestamps();
        });

        // 3. Transmissions
        Schema::create('transmissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Manual, Automatic, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transmissions');
        Schema::dropIfExists('fuel_types');
        Schema::dropIfExists('vehicle_categories');
    }
};
