<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 17. Performance Indicators
        Schema::create('performance_indicators', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type'); // Mechanic, Garage, Branch
            $table->unsignedBigInteger('entity_id');
            $table->string('indicator_name');
            $table->decimal('value', 15, 2);
            $table->string('period');
            $table->timestamps();
        });

        // 18. Audit Metrics
        Schema::create('audit_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('metric_name');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->timestamp('changed_at')->useCurrent();
        });

        // 19. Alert Rules (Indicator based)
        Schema::create('bi_alert_rules', function (Blueprint $table) {
            $table->id();
            $table->string('owner_type');
            $table->unsignedBigInteger('owner_id');
            $table->string('metric_name');
            $table->string('operator'); // <, >, <=, >=
            $table->decimal('threshold', 15, 2);
            $table->string('notification_channel')->default('PUSH');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 20. Benchmarks
        Schema::create('benchmarks', function (Blueprint $table) {
            $table->id();
            $table->string('benchmark_type'); // VS_PREVIOUS_PERIOD, VS_AVERAGE
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id');
            $table->string('reference_period');
            $table->string('comparison_period');
            $table->decimal('result_percentage', 8, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('benchmarks');
        Schema::dropIfExists('bi_alert_rules');
        Schema::dropIfExists('audit_metrics');
        Schema::dropIfExists('performance_indicators');
    }
};
