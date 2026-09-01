<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 4. Device Management
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('device_type'); // ANDROID, IOS, WEB
            $table->string('device_token')->unique();
            $table->string('platform')->nullable();
            $table->timestamp('last_active')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 5. Push Logs
        Schema::create('push_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_id')->constrained('notifications')->onDelete('cascade');
            $table->foreignId('device_id')->constrained('user_devices')->onDelete('cascade');
            $table->string('status'); // PENDING, SENT, FAILED
            $table->timestamp('sent_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });

        // 6. Email Notifications
        Schema::create('email_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_id')->constrained('notifications')->onDelete('cascade');
            $table->string('email_address');
            $table->string('subject');
            $table->text('body');
            $table->string('status')->default('PENDING');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        // 7. SMS Notifications
        Schema::create('sms_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_id')->constrained('notifications')->onDelete('cascade');
            $table->string('phone_number');
            $table->text('message');
            $table->string('status')->default('PENDING');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_notifications');
        Schema::dropIfExists('email_notifications');
        Schema::dropIfExists('push_logs');
        Schema::dropIfExists('user_devices');
    }
};
