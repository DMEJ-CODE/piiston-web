<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. AI Providers
        Schema::create('ai_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // OpenAI, Google, Anthropic
            $table->string('provider_code')->unique();
            $table->string('api_endpoint')->nullable();
            $table->string('authentication_type')->default('bearer');
            $table->string('default_model')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 2. AI Models
        Schema::create('ai_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('ai_providers')->onDelete('cascade');
            $table->string('model_name'); // gpt-4o, gemini-1.5-pro
            $table->string('version')->nullable();
            $table->string('purpose'); // chat, vision, embedding
            $table->integer('max_tokens')->nullable();
            $table->boolean('supports_images')->default(false);
            $table->boolean('supports_audio')->default(false);
            $table->boolean('supports_documents')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 3. AI Assistants
        Schema::create('ai_assistants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('assistant_type'); // GENERAL, DIAGNOSIS, FLEET, etc.
            $table->text('description')->nullable();
            $table->foreignId('default_model_id')->nullable()->constrained('ai_models');
            $table->json('language_support')->nullable(); // ["fr", "en"]
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_assistants');
        Schema::dropIfExists('ai_models');
        Schema::dropIfExists('ai_providers');
    }
};
