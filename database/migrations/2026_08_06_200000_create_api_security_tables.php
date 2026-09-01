<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop existing simple api_clients if it exists
        Schema::dropIfExists('api_clients');

        // 1. API Clients (Internal or External apps)
        Schema::create('api_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('PARTNER'); // INTERNAL, PARTNER, PUBLIC
            $table->string('owner_type')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 2. API Keys
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_client_id')->constrained('api_clients')->onDelete('cascade');
            $table->string('key_hash')->unique();
            $table->string('secret_hash')->nullable();
            $table->json('permissions')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 3. API Permissions (Granular scopes)
        Schema::create('api_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // vehicles.read, repairs.write
            $table->string('scope'); // users, marketplace, fleet
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_permissions');
        Schema::dropIfExists('api_keys');
        Schema::dropIfExists('api_clients');
    }
};
