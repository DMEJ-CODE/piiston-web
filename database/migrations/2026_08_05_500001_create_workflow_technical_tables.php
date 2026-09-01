<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 6. Diagnostic Measurements
        Schema::create('diagnostic_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diagnosis_id')->constrained('vehicle_diagnoses')->onDelete('cascade');
            $table->string('parameter');
            $table->string('value');
            $table->string('unit')->nullable();
            $table->string('reference_value')->nullable();
            $table->string('status')->nullable(); // OK, HIGH, LOW, FAULTY
            $table->timestamps();
        });

        // 7. Vehicle Fault Codes (OBD)
        Schema::create('vehicle_fault_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diagnosis_id')->constrained('vehicle_diagnoses')->onDelete('cascade');
            $table->string('code', 20); // e.g. P0420
            $table->text('description')->nullable();
            $table->string('severity')->default('medium');
            $table->text('solution')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_fault_codes');
        Schema::dropIfExists('diagnostic_measurements');
    }
};
