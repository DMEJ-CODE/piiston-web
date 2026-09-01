<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 24. Admin Broadcast System
        Schema::create('admin_broadcasts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('administrators');
            $table->string('title');
            $table->text('message');
            $table->json('target_roles')->nullable(); // ["CLIENTS", "MECHANICS"]
            $table->json('channels')->nullable(); // ["PUSH", "SMS"]
            $table->dateTime('scheduled_at')->nullable();
            $table->string('status')->default('PENDING'); // PENDING, SENT, CANCELLED
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_broadcasts');
    }
};
