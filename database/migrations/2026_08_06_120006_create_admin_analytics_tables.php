<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 15. Analytics Caching
        Schema::create('platform_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('metric_name'); // active_users, total_revenue, etc.
            $table->decimal('metric_value', 15, 2);
            $table->string('period'); // daily, weekly, monthly
            $table->date('stat_date');
            $table->timestamps();
        });

        // 22. Administrative Reports
        Schema::create('platform_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_type'); // Financial, UserActivity, ProfessionalGrowth
            $table->foreignId('generated_by')->constrained('administrators');
            $table->string('file_path');
            $table->dateTime('generated_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_reports');
        Schema::dropIfExists('platform_statistics');
    }
};
