<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. KPIs (Definitions)
        Schema::create('kpis', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // monthly_revenue, avg_repair_time
            $table->string('category'); // Finance, Repair, Marketplace, etc.
            $table->text('formula')->nullable(); // Logic description
            $table->string('unit')->nullable(); // %, FCFA, hours
            $table->decimal('target_value', 15, 2)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 2. Metrics (Calculated values)
        Schema::create('metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kpi_id')->constrained('kpis')->onDelete('cascade');
            $table->decimal('value', 15, 2);
            $table->string('period'); // daily, monthly, yearly
            $table->foreignId('country_id')->nullable()->constrained('countries');
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->timestamp('calculated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metrics');
        Schema::dropIfExists('kpis');
    }
};
