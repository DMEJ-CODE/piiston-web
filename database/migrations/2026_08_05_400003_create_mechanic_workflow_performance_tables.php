<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 12. Mechanic Job Assignments
        Schema::create('mechanic_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mechanic_id')->constrained('mechanic_profiles')->onDelete('cascade');
            $table->foreignId('repair_order_id')->constrained('repair_orders')->onDelete('cascade');
            $table->foreignId('assigned_by')->constrained('users');
            $table->timestamp('assigned_date')->useCurrent();
            $table->string('status')->default('ASSIGNED');
            $table->timestamps();
        });

        // 13. Mechanic Performance
        Schema::create('mechanic_performances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mechanic_id')->constrained('mechanic_profiles')->onDelete('cascade');
            $table->string('period'); // monthly, yearly
            $table->integer('completed_jobs')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->decimal('customer_satisfaction', 5, 2)->default(0);
            $table->integer('average_completion_time_minutes')->default(0);
            $table->timestamps();
        });

        // 14. Mechanic Reviews
        Schema::create('mechanic_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mechanic_id')->constrained('mechanic_profiles')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('repair_order_id')->nullable()->constrained('repair_orders');
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        // 15. Mechanic Payments (For independents)
        Schema::create('mechanic_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mechanic_id')->constrained('mechanic_profiles')->onDelete('cascade');
            $table->string('service_description');
            $table->decimal('amount', 12, 2);
            $table->foreignId('currency_id')->constrained('currencies');
            $table->string('payment_status')->default('pending');
            $table->dateTime('payment_date')->nullable();
            $table->timestamps();
        });

        // 16. Mechanic Emergency Services
        Schema::create('mechanic_emergency_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mechanic_id')->nullable()->constrained('mechanic_profiles');
            $table->string('location_description');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->foreignId('vehicle_id')->constrained('vehicles');
            $table->text('problem_description');
            $table->string('status')->default('REQUESTED');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mechanic_emergency_services');
        Schema::dropIfExists('mechanic_payments');
        Schema::dropIfExists('mechanic_reviews');
        Schema::dropIfExists('mechanic_performances');
        Schema::dropIfExists('mechanic_assignments');
    }
};
