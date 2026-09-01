<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 11. Archives
        Schema::create('conversation_archives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('archived_at')->useCurrent();
            $table->timestamps();
        });

        // 12. Individual Settings
        Schema::create('conversation_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_muted')->default(false);
            $table->timestamp('mute_until')->nullable();
            $table->timestamps();
        });

        // 18. Notifications summary
        Schema::create('chat_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('messages')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('sent_status')->default('PENDING');
            $table->timestamps();
        });

        // 21. AI Chat Preparation
        Schema::create('ai_chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('context_type')->nullable();
            $table->unsignedBigInteger('context_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_chat_sessions');
        Schema::dropIfExists('chat_notifications');
        Schema::dropIfExists('conversation_settings');
        Schema::dropIfExists('conversation_archives');
    }
};
