<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Locations (Polymorphic base)
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type'); // User, Garage, Mechanic, etc.
            $table->unsignedBigInteger('entity_id');
            $table->foreignId('country_id')->nullable()->constrained('countries');
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->foreignId('address_id')->nullable()->constrained('addresses');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->decimal('altitude', 10, 2)->nullable();
            $table->decimal('accuracy', 8, 2)->nullable();
            $table->string('source')->nullable(); // gps, network, ip
            $table->string('status')->default('active');
            $table->timestamps();

            $table->index(['entity_type', 'entity_id']);
        });

        // 3. GeoPoints
        Schema::create('geo_points', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('type'); // Garage, Parking, Fuel, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('geo_points');
        Schema::dropIfExists('locations');
    }
};
