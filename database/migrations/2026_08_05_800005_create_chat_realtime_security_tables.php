<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 13. Online Presence
        Schema::create('user_presence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('status')->default('OFFLINE'); // ONLINE, OFFLINE, BUSY
            $table->timestamp('last_seen')->nullable();
            $table->timestamps();
        });

        // 14. Typing Status
        Schema::create('typing_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('started_at')->useCurrent();
            $table->timestamps();
            $table->unique(['conversation_id', 'user_id']);
        });

        // 15. Block System
        Schema::create('blocked_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blocker_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('blocked_user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['blocker_id', 'blocked_user_id']);
        });

        // 16. Reporting System
        Schema::create('message_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('messages')->onDelete('cascade');
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
            $table->text('reason');
            $table->string('status')->default('PENDING'); // PENDING, REVIEWED, ACTION_TAKEN
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_reports');
        Schema::dropIfExists('blocked_users');
        Schema::dropIfExists('typing_status');
        Schema::dropIfExists('user_presence');
    }
};
