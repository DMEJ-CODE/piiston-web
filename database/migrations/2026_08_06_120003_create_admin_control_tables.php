<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 10. Platform Configuration
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key')->unique();
            $table->text('setting_value');
            $table->string('description')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('administrators');
            $table->timestamps();
        });

        // 20. API Management
        Schema::create('api_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('api_key')->unique();
            $table->json('permissions')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 21. Feature Flags
        Schema::create('feature_flags', function (Blueprint $table) {
            $table->id();
            $table->string('feature_name')->unique();
            $table->boolean('enabled')->default(false);
            $table->string('target_country')->nullable(); // ISO code or ALL
            $table->string('target_role')->nullable(); // Role name or ALL
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_flags');
        Schema::dropIfExists('api_clients');
        Schema::dropIfExists('system_settings');
    }
};
