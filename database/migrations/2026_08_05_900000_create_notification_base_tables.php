<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 2. Notification Types
        Schema::create('notification_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // ACCOUNT_CREATED, REPAIR_COMPLETED, etc.
            $table->string('category'); // Account, Repair, Marketplace, etc.
            $table->string('description')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 1. Notifications (Main storage)
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('type_id')->constrained('notification_types');
            $table->string('title');
            $table->text('message');
            $table->string('priority')->default('medium'); // low, medium, high, critical
            $table->string('reference_type')->nullable(); // RepairOrder, Order, etc.
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('notification_types');
    }
};
