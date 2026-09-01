<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('repair_orders', function (Blueprint $table) {
            $table->foreignId('workshop_bay_id')->nullable()->after('appointment_id')->constrained('workshop_bays');
        });
    }

    public function down(): void
    {
        Schema::table('repair_orders', function (Blueprint $table) {
            $table->dropForeign(['workshop_bay_id']);
            $table->dropColumn('workshop_bay_id');
        });
    }
};
