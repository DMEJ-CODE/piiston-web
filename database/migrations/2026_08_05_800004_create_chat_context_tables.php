<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 17. Business Context Chat
        Schema::create('chat_contexts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->string('context_type'); // REPAIR_ORDER, APPOINTMENT, ORDER, EMERGENCY
            $table->unsignedBigInteger('context_id');
            $table->timestamps();
        });

        // 19. Group Metadata
        Schema::create('chat_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->string('group_name');
            $table->string('group_image')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // 20. Group Permissions
        Schema::create('group_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('chat_groups')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('can_send_message')->default(true);
            $table->boolean('can_add_member')->default(false);
            $table->boolean('can_remove_member')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_permissions');
        Schema::dropIfExists('chat_groups');
        Schema::dropIfExists('chat_contexts');
    }
};
