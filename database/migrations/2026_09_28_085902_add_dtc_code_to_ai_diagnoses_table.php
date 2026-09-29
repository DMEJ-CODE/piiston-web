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
        Schema::table('ai_diagnoses', function (Blueprint $table) {
            if (! Schema::hasColumn('ai_diagnoses', 'dtc_code')) {
                $table->string('dtc_code')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_diagnoses', function (Blueprint $table) {
            if (Schema::hasColumn('ai_diagnoses', 'dtc_code')) {
                $table->dropColumn('dtc_code');
            }
        });
    }
};
