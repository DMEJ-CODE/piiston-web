<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 11. Webhooks
        Schema::create('webhooks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id'); // Can be a User or ApiClient
            $table->string('owner_type');
            $table->string('url');
            $table->string('event_type'); // repair.completed, order.placed
            $table->string('secret_token')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 12. Webhook Events
        Schema::create('webhook_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_name');
            $table->json('payload');
            $table->timestamp('created_at')->useCurrent();
        });

        // 13. Webhook Deliveries
        Schema::create('webhook_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webhook_id')->constrained('webhooks')->onDelete('cascade');
            $table->foreignId('event_id')->constrained('webhook_events')->onDelete('cascade');
            $table->string('status')->default('PENDING'); // SENT, FAILED, RETRYING
            $table->integer('attempts')->default(0);
            $table->timestamp('last_attempt_at')->nullable();
            $table->integer('response_code')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_deliveries');
        Schema::dropIfExists('webhook_events');
        Schema::dropIfExists('webhooks');
    }
};
