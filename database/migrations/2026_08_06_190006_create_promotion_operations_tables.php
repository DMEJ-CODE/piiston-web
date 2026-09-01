<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 21. Promotion Schedules
        Schema::create('promotion_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained('promotions')->onDelete('cascade');
            $table->string('frequency')->nullable();
            $table->dateTime('next_run_at')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // 22. Promotion Notifications
        Schema::create('promotion_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained('promotions')->onDelete('cascade');
            $table->unsignedBigInteger('notification_id'); // Link to Notification module
            $table->timestamps();
        });

        // 23. Lead Tracking
        Schema::create('promotion_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained('promotions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action_taken'); // CONTACTED, REQUESTED_QUOTE
            $table->string('status')->default('NEW');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_leads');
        Schema::dropIfExists('promotion_notifications');
        Schema::dropIfExists('promotion_schedules');
    }
};
