<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 15. Fleet Expenses (Global Ledger)
        Schema::create('fleet_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fleet_id')->constrained('fleets')->onDelete('cascade');
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->onDelete('set null');
            $table->string('expense_type'); // Fuel, Repair, Insurance, Maintenance, Tax, etc.
            $table->decimal('amount', 12, 2);
            $table->foreignId('currency_id')->constrained('currencies');
            $table->dateTime('expense_date');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 16. Fleet Reports (Aggregated Data)
        Schema::create('fleet_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fleet_id')->constrained('fleets')->onDelete('cascade');
            $table->string('period_name'); // e.g. "July 2026"
            $table->decimal('total_distance_km', 12, 2)->default(0);
            $table->decimal('total_cost', 15, 2)->default(0);
            $table->decimal('fuel_consumption_total', 12, 2)->default(0);
            $table->integer('maintenance_count')->default(0);
            $table->dateTime('generated_at');
            $table->timestamps();
        });

        // 17. Fleet Alerts (Automated)
        Schema::create('fleet_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fleet_id')->constrained('fleets')->onDelete('cascade');
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->onDelete('set null');
            $table->string('type'); // Maintenance Due, Insurance Expiry, Accident, etc.
            $table->text('message');
            $table->string('priority')->default('MEDIUM');
            $table->boolean('is_resolved')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fleet_alerts');
        Schema::dropIfExists('fleet_reports');
        Schema::dropIfExists('fleet_expenses');
    }
};
