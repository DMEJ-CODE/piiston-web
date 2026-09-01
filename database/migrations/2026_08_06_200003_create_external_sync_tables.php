<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 19. External Services
        Schema::create('external_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('service_type'); // API, SFTP, DB
            $table->string('endpoint');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 20. External Syncs
        Schema::create('external_syncs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('integration_id')->constrained('integrations')->onDelete('cascade');
            $table->string('entity_type'); // Product, User
            $table->unsignedBigInteger('entity_id');
            $table->string('sync_status')->default('PENDING');
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamps();
        });

        // 21. External Mappings
        Schema::create('external_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('integration_id')->constrained('integrations')->onDelete('cascade');
            $table->string('local_field');
            $table->string('external_field');
            $table->timestamps();
        });

        // 23. Sync Queue
        Schema::create('sync_queue', function (Blueprint $table) {
            $table->id();
            $table->foreignId('integration_id')->constrained('integrations')->onDelete('cascade');
            $table->string('entity');
            $table->string('operation'); // PULL, PUSH
            $table->string('status')->default('WAITING');
            $table->integer('retry_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sync_queue');
        Schema::dropIfExists('external_mappings');
        Schema::dropIfExists('external_syncs');
        Schema::dropIfExists('external_services');
    }
};
