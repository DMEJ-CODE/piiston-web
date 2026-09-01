<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feature_flags', function (Blueprint $table) {
            $table->text('description')->nullable()->after('enabled');
            $table->unsignedTinyInteger('rollout_percentage')->default(100)->after('target_role');
        });
    }

    public function down(): void
    {
        Schema::table('feature_flags', function (Blueprint $table) {
            $table->dropColumn(['description', 'rollout_percentage']);
        });
    }
};
