<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 4. Brands
        Schema::create('vehicle_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('logo')->nullable();
            $table->string('country_origin')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 5. Models
        Schema::create('vehicle_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained('vehicle_brands')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('vehicle_categories');
            $table->string('name');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 6. Generations
        Schema::create('vehicle_generations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('model_id')->constrained('vehicle_models')->onDelete('cascade');
            $table->string('generation_name');
            $table->integer('start_year');
            $table->integer('end_year')->nullable();
            $table->timestamps();
        });

        // 7. Engines
        Schema::create('vehicle_engines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('generation_id')->constrained('vehicle_generations')->onDelete('cascade');
            $table->string('engine_code')->nullable();
            $table->string('fuel_type'); // Reference or string
            $table->string('capacity')->nullable(); // e.g., 2.0L
            $table->integer('horsepower')->nullable();
            $table->integer('cylinder')->nullable();
            $table->boolean('turbo')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_engines');
        Schema::dropIfExists('vehicle_generations');
        Schema::dropIfExists('vehicle_models');
        Schema::dropIfExists('vehicle_brands');
    }
};
