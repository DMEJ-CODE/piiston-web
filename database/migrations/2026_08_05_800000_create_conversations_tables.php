<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Conversations
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->string('conversation_type'); // PRIVATE_CHAT, GROUP_CHAT, REPAIR_CHAT, etc.
            $table->string('name')->nullable();
            $table->string('avatar')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->unsignedBigInteger('last_message_id')->nullable(); // Set after messages created
            $table->timestamps();
        });

        // 2. Conversation Members
        Schema::create('conversation_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('role')->default('MEMBER'); // OWNER, ADMIN, MEMBER
            $table->timestamp('joined_at')->useCurrent();
            $table->unsignedBigInteger('last_seen_message_id')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->string('notification_status')->default('enabled');
            $table->timestamps();
            $table->unique(['conversation_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversation_members');
        Schema::dropIfExists('conversations');
    }
};
