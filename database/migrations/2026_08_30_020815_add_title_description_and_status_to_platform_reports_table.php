<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('platform_reports', function (Blueprint $table) {
            $table->string('title')->after('report_type');
            $table->text('description')->nullable()->after('title');
            $table->string('status')->default('pending')->after('generated_at');
        });
    }

    public function down(): void
    {
        Schema::table('platform_reports', function (Blueprint $table) {
            $table->dropColumn(['status', 'description', 'title']);
        });
    }
};
