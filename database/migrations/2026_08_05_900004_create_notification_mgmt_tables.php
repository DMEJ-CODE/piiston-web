<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 12. System Announcements
        Schema::create('system_announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->string('target_role')->default('ALL_USERS'); // CLIENTS, MECHANICS, etc.
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 14. Global History (Audit Trail)
        Schema::create('notification_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action'); // CREATED, SENT, READ, DELETED
            $table->foreignId('notification_id')->nullable()->constrained('notifications')->onDelete('set null');
            $table->timestamp('occurred_at')->useCurrent();
            $table->timestamps();
        });

        // 15. Templates
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('channel'); // push, email, sms
            $table->string('title_template');
            $table->text('body_template');
            $table->json('variables')->nullable(); // List of expected variables like {name}, {vehicle}
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_templates');
        Schema::dropIfExists('notification_history');
        Schema::dropIfExists('system_announcements');
    }
};
