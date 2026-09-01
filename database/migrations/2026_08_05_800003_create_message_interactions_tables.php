<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 5. Message Reactions
        Schema::create('message_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('messages')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('reaction'); // emoji or string code
            $table->timestamps();
            $table->unique(['message_id', 'user_id', 'reaction']);
        });

        // 9. Forwarded Messages
        Schema::create('forwarded_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('messages')->onDelete('cascade'); // The new message
            $table->unsignedBigInteger('original_message_id');
            $table->foreign('original_message_id')->references('id')->on('messages')->onDelete('cascade');
            $table->foreignId('forwarded_by')->constrained('users');
            $table->timestamps();
        });

        // 11. Chat Search Indexes
        Schema::create('message_indexes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('messages')->onDelete('cascade');
            $table->text('keywords'); // Full-text search content
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_indexes');
        Schema::dropIfExists('forwarded_messages');
        Schema::dropIfExists('message_reactions');
    }
};
