<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 4. Garage Employees
        Schema::create('garage_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('garage_branches')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('department_id')->nullable()->constrained('garage_departments');
            $table->string('employee_number')->nullable()->unique();
            $table->string('position'); // OWNER, MANAGER, MECHANIC, etc.
            $table->string('employment_type')->default('full-time');
            $table->date('hire_date')->nullable();
            $table->decimal('salary', 12, 2)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 5. Employee Schedules
        Schema::create('employee_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('garage_employees')->onDelete('cascade');
            $table->string('day'); // Monday, Tuesday, etc.
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 6. Garage Customers
        Schema::create('garage_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('garage_branches')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('customer_type')->default('Individual'); // Individual, Company, Fleet
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garage_customers');
        Schema::dropIfExists('employee_schedules');
        Schema::dropIfExists('garage_employees');
    }
};
