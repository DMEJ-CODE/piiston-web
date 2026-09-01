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
        Schema::table('emergency_requests', function (Blueprint $blueprint) {
            $blueprint->decimal('latitude', 10, 8)->nullable();
            $blueprint->decimal('longitude', 11, 8)->nullable();
            $blueprint->integer('radius_km')->default(10);
            $blueprint->unsignedBigInteger('branch_id')->nullable(); // The winning garage
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emergency_requests', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['latitude', 'longitude', 'radius_km', 'branch_id']);
        });
    }
};
