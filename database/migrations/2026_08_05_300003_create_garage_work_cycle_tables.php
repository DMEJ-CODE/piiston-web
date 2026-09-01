<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 7. Vehicle Check-ins
        Schema::create('vehicle_check_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('garage_branches')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained('vehicles');
            $table->foreignId('customer_id')->constrained('garage_customers');
            $table->foreignId('received_by')->constrained('users');
            $table->dateTime('arrival_date');
            $table->bigInteger('mileage')->nullable();
            $table->string('fuel_level')->nullable();
            $table->text('vehicle_condition')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('WAITING');
            $table->timestamps();
        });

        // 9. Appointments
        Schema::create('garage_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('garage_branches')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('garage_customers');
            $table->foreignId('vehicle_id')->constrained('vehicles');
            $table->foreignId('service_id')->nullable()->constrained('garage_services');
            $table->dateTime('scheduled_date');
            $table->integer('duration_minutes')->default(60);
            $table->string('status')->default('REQUESTED');
            $table->timestamps();
        });

        // 10. Repair Orders
        Schema::create('repair_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('garage_branches')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained('vehicles');
            $table->foreignId('customer_id')->constrained('garage_customers');
            $table->foreignId('appointment_id')->nullable()->constrained('garage_appointments');
            $table->foreignId('assigned_mechanic_id')->nullable()->constrained('users');
            $table->text('problem_description');
            $table->string('priority')->default('normal');
            $table->string('status')->default('OPEN');
            $table->decimal('estimated_cost', 12, 2)->nullable();
            $table->decimal('final_cost', 12, 2)->nullable();
            $table->dateTime('opened_at')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->timestamps();
        });

        // 11. Vehicle Diagnoses
        Schema::create('vehicle_diagnoses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_order_id')->constrained('repair_orders')->onDelete('cascade');
            $table->foreignId('mechanic_id')->constrained('users');
            $table->text('symptoms')->nullable();
            $table->text('detected_problem')->nullable();
            $table->text('root_cause')->nullable();
            $table->text('solution')->nullable();
            $table->text('recommendation')->nullable();
            $table->string('severity')->default('medium');
            $table->timestamps();
        });

        // 12. Repair Tasks
        Schema::create('repair_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_order_id')->constrained('repair_orders')->onDelete('cascade');
            $table->foreignId('assigned_employee_id')->nullable()->constrained('garage_employees');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('pending');
            $table->integer('estimated_time_minutes')->nullable();
            $table->integer('actual_time_minutes')->nullable();
            $table->timestamps();
        });

        // 14. Repair Estimates
        Schema::create('repair_estimates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_order_id')->constrained('repair_orders')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->string('status')->default('PENDING');
            $table->timestamps();
        });

        // 15. Repair Parts
        Schema::create('repair_parts_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_order_id')->constrained('repair_orders')->onDelete('cascade');
            $table->foreignId('part_id')->nullable(); // Link to Marketplace/Inventory part
            $table->string('part_name')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repair_parts_usage');
        Schema::dropIfExists('repair_estimates');
        Schema::dropIfExists('repair_tasks');
        Schema::dropIfExists('vehicle_diagnoses');
        Schema::dropIfExists('repair_orders');
        Schema::dropIfExists('garage_appointments');
        Schema::dropIfExists('vehicle_check_ins');
    }
};
