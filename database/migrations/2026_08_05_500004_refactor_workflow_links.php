<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add links to garage_appointments
        Schema::table('garage_appointments', function (Blueprint $table) {
            $table->foreignId('request_id')->nullable()->after('id')->constrained('service_requests');
        });

        // Add links to vehicle_check_ins
        Schema::table('vehicle_check_ins', function (Blueprint $table) {
            $table->foreignId('appointment_id')->nullable()->after('id')->constrained('garage_appointments');
        });

        // Add links to repair_orders
        Schema::table('repair_orders', function (Blueprint $table) {
            $table->foreignId('check_in_id')->nullable()->after('id')->constrained('vehicle_check_ins');
        });
    }

    public function down(): void
    {
        Schema::table('repair_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('check_in_id');
        });
        Schema::table('vehicle_check_ins', function (Blueprint $table) {
            $table->dropConstrainedForeignId('appointment_id');
        });
        Schema::table('garage_appointments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('request_id');
        });
    }
};
