<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vehicle_deliveries', function (Blueprint $table) {
            $table->bigInteger('mileage_at_delivery')->nullable()->after('notes');
            $table->string('signature_path')->nullable()->after('mileage_at_delivery');
            $table->json('checklist')->nullable()->after('signature_path');
        });
    }

    public function down(): void
    {
        Schema::table('vehicle_deliveries', function (Blueprint $table) {
            $table->dropColumn(['mileage_at_delivery', 'signature_path', 'checklist']);
        });
    }
};
