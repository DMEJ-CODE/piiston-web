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
        Schema::table('garage_branches', function (Blueprint $table) {
            $table->json('business_hours')->nullable()->after('email');
            $table->json('social_links')->nullable()->after('business_hours');
            $table->string('logo_path')->nullable()->after('name');
            $table->string('cover_path')->nullable()->after('logo_path');
            $table->decimal('latitude', 10, 8)->nullable()->after('address_id');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('garage_branches', function (Blueprint $table) {
            $table->dropColumn(['business_hours', 'social_links', 'logo_path', 'cover_path', 'latitude', 'longitude']);
        });
    }
};
