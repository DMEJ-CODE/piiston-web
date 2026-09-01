<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type'); // VEHICLE_CREATED, SOS_REQUESTED, etc.
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->nullableMorphs('subject'); // Polymorphic relation to Vehicle, Order, etc.
            $table->json('data')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_activities');
    }
};
