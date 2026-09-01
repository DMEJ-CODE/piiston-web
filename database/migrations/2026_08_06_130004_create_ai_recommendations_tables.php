<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 12. AI Garage Recommendations
        Schema::create('ai_garage_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diagnosis_id')->constrained('ai_diagnoses')->onDelete('cascade');
            $table->foreignId('garage_id')->constrained('garage_companies')->onDelete('cascade');
            $table->integer('recommendation_score')->default(0);
            $table->decimal('estimated_distance_km', 8, 2)->nullable();
            $table->text('reason')->nullable();
            $table->timestamps();
        });

        // 13. AI Mechanic Recommendations
        Schema::create('ai_mechanic_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diagnosis_id')->constrained('ai_diagnoses')->onDelete('cascade');
            $table->foreignId('mechanic_id')->constrained('mechanic_profiles')->onDelete('cascade');
            $table->integer('recommendation_score')->default(0);
            $table->text('reason')->nullable();
            $table->timestamps();
        });

        // 14. AI Part Recommendations
        Schema::create('ai_part_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diagnosis_id')->constrained('ai_diagnoses')->onDelete('cascade');
            $table->foreignId('part_id')->constrained('spare_parts')->onDelete('cascade');
            $table->integer('compatibility_score')->default(0);
            $table->string('priority')->default('MEDIUM');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_part_recommendations');
        Schema::dropIfExists('ai_mechanic_recommendations');
        Schema::dropIfExists('ai_garage_recommendations');
    }
};
