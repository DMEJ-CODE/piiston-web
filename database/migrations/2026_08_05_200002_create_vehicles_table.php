<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('country_id')->constrained('countries');
            $table->foreignId('brand_id')->constrained('vehicle_brands');
            $table->foreignId('model_id')->constrained('vehicle_models');
            $table->foreignId('generation_id')->nullable()->constrained('vehicle_generations');
            $table->integer('year')->nullable();
            $table->string('vin')->nullable()->unique();
            $table->string('registration_number')->nullable()->unique();
            $table->string('license_plate')->nullable();
            $table->string('color')->nullable();
            $table->foreignId('fuel_type_id')->nullable()->constrained('fuel_types');
            $table->foreignId('transmission_id')->nullable()->constrained('transmissions');
            $table->bigInteger('mileage')->default(0);
            $table->string('engine_number')->nullable();
            $table->string('status')->default('active'); // active, maintenance, sold, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
