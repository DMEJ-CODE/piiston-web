<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 4. Map Providers
        Schema::create('map_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Google Maps, OpenStreetMap, Mapbox
            $table->string('provider_code')->unique();
            $table->string('api_key_reference')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // Map Layers
        Schema::create('map_layers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('layer_type'); // Garages, Mechanics, Traffic, Delivery
            $table->foreignId('provider_id')->constrained('map_providers')->onDelete('cascade');
            $table->boolean('visibility')->default(true);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('map_layers');
        Schema::dropIfExists('map_providers');
    }
};
