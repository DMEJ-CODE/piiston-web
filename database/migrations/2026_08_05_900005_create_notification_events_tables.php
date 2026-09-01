<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 13. Marketing Campaigns
        Schema::create('marketing_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('campaign_name');
            $table->string('title');
            $table->text('content');
            $table->json('target_users_criteria')->nullable();
            $table->dateTime('scheduled_at');
            $table->string('status')->default('draft');
            $table->timestamps();
        });

        // 16. Event Ledger
        Schema::create('system_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_name'); // REPAIR_COMPLETED, ORDER_PLACED, etc.
            $table->string('source_type')->nullable(); // Model name
            $table->unsignedBigInteger('source_id')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_events');
        Schema::dropIfExists('marketing_notifications');
    }
};
