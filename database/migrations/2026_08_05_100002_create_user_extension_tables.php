<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 6. User Addresses
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('address_id')->constrained('addresses')->onDelete('cascade');
            $table->string('type')->default('HOME'); // HOME, WORK, OTHER
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // 8. User Verifications
        Schema::create('user_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('verification_type'); // IDENTITY, BUSINESS, PROFESSIONAL
            $table->foreignId('document_type_id')->constrained('document_types');
            $table->string('document_number');
            $table->string('document_file');
            $table->string('status')->default('pending'); // pending, verified, rejected
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        // 9. Devices
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('device_token')->unique();
            $table->string('platform'); // ios, android, web
            $table->string('app_version')->nullable();
            $table->timestamp('last_used')->nullable();
            $table->timestamps();
        });

        // 10. User Preferences
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('notification_enabled')->default(true);
            $table->foreignId('language_id')->nullable()->constrained('languages');
            $table->boolean('dark_mode')->default(false);
            $table->boolean('location_enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
        Schema::dropIfExists('devices');
        Schema::dropIfExists('user_verifications');
        Schema::dropIfExists('user_addresses');
    }
};
