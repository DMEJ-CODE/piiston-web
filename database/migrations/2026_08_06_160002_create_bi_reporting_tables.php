<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 5. Report Templates
        Schema::create('report_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('report_type'); // FINANCIAL, INVENTORY, ACTIVITY
            $table->json('configuration')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 6. Reports (Instance of generated report)
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('owner_type');
            $table->unsignedBigInteger('owner_id');
            $table->string('report_type');
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('generated_by')->constrained('users');
            $table->timestamp('generated_at');
            $table->timestamps();
        });

        // 7. Report Executions
        Schema::create('report_executions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->onDelete('cascade');
            $table->string('status'); // PENDING, SUCCESS, FAILED
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('execution_time_ms')->nullable();
        });

        // 8. Scheduled Reports
        Schema::create('scheduled_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_template_id')->constrained('report_templates')->onDelete('cascade');
            $table->string('owner_type');
            $table->unsignedBigInteger('owner_id');
            $table->string('frequency'); // DAILY, WEEKLY, MONTHLY
            $table->string('delivery_method'); // EMAIL, IN_APP
            $table->timestamp('next_execution_at')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scheduled_reports');
        Schema::dropIfExists('report_executions');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('report_templates');
    }
};
