<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 5. Spare Parts
        Schema::create('spare_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('part_categories');
            $table->foreignId('brand_id')->constrained('part_brands');
            $table->string('name');
            $table->string('part_number')->unique();
            $table->text('description')->nullable();
            $table->string('condition'); // NEW, USED, REFURBISHED
            $table->string('quality_grade'); // OEM, ORIGINAL, AFTERMARKET
            $table->string('warranty_period')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 6. Vehicle Compatibility
        Schema::create('part_vehicle_compatibilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_id')->constrained('spare_parts')->onDelete('cascade');
            $table->foreignId('vehicle_brand_id')->constrained('vehicle_brands');
            $table->foreignId('vehicle_model_id')->constrained('vehicle_models');
            $table->foreignId('generation_id')->nullable()->constrained('vehicle_generations');
            $table->integer('year_from')->nullable();
            $table->integer('year_to')->nullable();
            $table->string('engine_type')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('part_vehicle_compatibilities');
        Schema::dropIfExists('spare_parts');
    }
};
