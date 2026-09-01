<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 12. Charts
        Schema::create('charts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dashboard_widget_id')->constrained('dashboard_widgets')->onDelete('cascade');
            $table->string('chart_type'); // PIE, BAR, LINE, GAUGE
            $table->json('configuration')->nullable();
            $table->timestamps();
        });

        // 13. Data Sources
        Schema::create('data_sources', function (Blueprint $table) {
            $table->id();
            $table->string('module_name'); // Marketplace, Fleet, Finance
            $table->string('table_name');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_sources');
        Schema::dropIfExists('charts');
    }
};
