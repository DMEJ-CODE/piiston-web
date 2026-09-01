<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 3. Messages
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users');
            $table->string('message_type')->default('TEXT'); // TEXT, IMAGE, VIDEO, VOICE, DOCUMENT, REPAIR_CARD, etc.
            $table->text('content')->nullable();
            $table->unsignedBigInteger('reply_message_id')->nullable();
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamp('edited_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('status')->default('SENT'); // global summary status
            $table->timestamps();
        });

        // 4. Message Statuses (Per recipient)
        Schema::create('message_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('messages')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('status'); // SENT, DELIVERED, READ
            $table->timestamp('timestamp')->useCurrent();
            $table->timestamps();
        });

        // Add backlink from conversation to messages
        Schema::table('conversations', function (Blueprint $table) {
            $table->foreign('last_message_id')->references('id')->on('messages')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropForeign(['last_message_id']);
        });
        Schema::dropIfExists('message_statuses');
        Schema::dropIfExists('messages');
    }
};
