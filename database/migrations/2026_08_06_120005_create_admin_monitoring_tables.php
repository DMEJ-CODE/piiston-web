<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 17. System Monitoring
        Schema::create('system_health_logs', function (Blueprint $table) {
            $table->id();
            $table->string('service'); // Database, Redis, S3, API
            $table->string('status'); // OK, DOWN, DEGRADED
            $table->integer('response_time_ms')->nullable();
            $table->timestamp('checked_at')->useCurrent();
        });

        // 18. Background Jobs
        Schema::create('background_job_logs', function (Blueprint $table) {
            $table->id();
            $table->string('job_name');
            $table->string('status'); // PENDING, PROCESSING, COMPLETED, FAILED
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });

        // 19. Backup Management
        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->string('backup_type'); // DATABASE, FILES, FULL
            $table->string('file_path');
            $table->bigInteger('size_bytes');
            $table->string('status')->default('COMPLETED');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backups');
        Schema::dropIfExists('background_job_logs');
        Schema::dropIfExists('system_health_logs');
    }
};
