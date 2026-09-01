<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 10. AI Diagnoses
        Schema::create('ai_diagnoses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('conversation_id')->nullable()->constrained('ai_conversations')->onDelete('set null');
            $table->text('symptoms');
            $table->json('possible_causes')->nullable();
            $table->json('recommended_actions')->nullable();
            $table->string('urgency_level')->default('MEDIUM'); // LOW, MEDIUM, HIGH, CRITICAL
            $table->integer('confidence_score')->default(0); // 0-100
            $table->timestamps();
        });

        // 11. AI Repair Suggestions
        Schema::create('ai_repair_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diagnosis_id')->constrained('ai_diagnoses')->onDelete('cascade');
            $table->string('repair_type');
            $table->integer('estimated_duration_minutes')->nullable();
            $table->decimal('estimated_cost_min', 15, 2)->nullable();
            $table->decimal('estimated_cost_max', 15, 2)->nullable();
            $table->integer('confidence_score')->default(0);
            $table->timestamps();
        });

        // 15. AI Maintenance Predictions
        Schema::create('ai_maintenance_predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->string('prediction_type'); // OIL_CHANGE, TIRE_ROTATION, etc.
            $table->date('predicted_date')->nullable();
            $table->bigInteger('predicted_mileage')->nullable();
            $table->integer('confidence_score')->default(0);
            $table->timestamps();
        });

        // 16. AI Fleet Insights
        Schema::create('ai_fleet_insights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fleet_id')->constrained('fleets')->onDelete('cascade');
            $table->string('insight_type'); // COST_ANOMALY, FUEL_OPTIMIZATION, PRODUCTIVITY
            $table->text('summary');
            $table->text('recommendation')->nullable();
            $table->string('priority')->default('MEDIUM');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_fleet_insights');
        Schema::dropIfExists('ai_maintenance_predictions');
        Schema::dropIfExists('ai_repair_suggestions');
        Schema::dropIfExists('ai_diagnoses');
    }
};
