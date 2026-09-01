<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 9. Labor Tracking
        Schema::create('labor_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_task_id')->constrained('repair_tasks')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('garage_employees');
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->decimal('hours', 8, 2)->nullable();
            $table->decimal('cost', 12, 2)->nullable();
            $table->timestamps();
        });

        // 11. Repair Progress Tracking
        Schema::create('repair_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_order_id')->constrained('repair_orders')->onDelete('cascade');
            $table->string('status');
            $table->string('message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repair_progress');
        Schema::dropIfExists('labor_records');
    }
};
