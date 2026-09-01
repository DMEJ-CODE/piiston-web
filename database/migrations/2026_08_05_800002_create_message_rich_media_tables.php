<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 6. Media Attachments
        Schema::create('message_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('messages')->onDelete('cascade');
            $table->string('file_type'); // IMAGE, VIDEO, AUDIO, PDF, etc.
            $table->string('file_url');
            $table->string('file_name')->nullable();
            $table->integer('file_size')->nullable();
            $table->integer('duration')->nullable(); // For video/audio
            $table->string('thumbnail')->nullable();
            $table->timestamps();
        });

        // 7. Voice Messages
        Schema::create('voice_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('messages')->onDelete('cascade');
            $table->string('audio_url');
            $table->integer('duration');
            $table->json('waveform_data')->nullable();
            $table->timestamps();
        });

        // 8. Shared Locations
        Schema::create('shared_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('messages')->onDelete('cascade');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shared_locations');
        Schema::dropIfExists('voice_messages');
        Schema::dropIfExists('message_media');
    }
};
