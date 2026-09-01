<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->morphs('interactable'); // For ProductListing, Service, etc.
            $table->string('type'); // LIKE, COMMENT, SHARE
            $table->text('content')->nullable(); // For comments
            $table->timestamps();

            $table->index(['interactable_id', 'interactable_type', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_interactions');
    }
};
