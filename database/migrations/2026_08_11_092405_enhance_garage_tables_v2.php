<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('repair_orders', function (Blueprint $table) {
            $table->json('status_history')->nullable()->after('status');
            $table->dateTime('quality_check_at')->nullable()->after('closed_at');
            $table->foreignId('quality_checked_by')->nullable()->after('quality_check_at')->constrained('users');
            $table->text('internal_notes')->nullable()->after('problem_description');
        });

        Schema::table('vehicle_check_ins', function (Blueprint $table) {
            $table->json('checklist')->nullable()->after('vehicle_condition');
            $table->string('signature_path')->nullable()->after('notes');
        });

        Schema::table('vehicle_diagnoses', function (Blueprint $table) {
            $table->json('dtc_codes')->nullable()->after('symptoms');
            $table->json('ai_hypotheses')->nullable()->after('recommendation');
            $table->boolean('is_ai_generated')->default(false)->after('severity');
        });
    }

    public function down(): void
    {
        Schema::table('repair_orders', function (Blueprint $table) {
            $table->dropColumn(['status_history', 'quality_check_at', 'quality_checked_by', 'internal_notes']);
        });

        Schema::table('vehicle_check_ins', function (Blueprint $table) {
            $table->dropColumn(['checklist', 'signature_path']);
        });

        Schema::table('vehicle_diagnoses', function (Blueprint $table) {
            $table->dropColumn(['dtc_codes', 'ai_hypotheses', 'is_ai_generated']);
        });
    }
};
