<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 6. AI Prompt Templates
        Schema::create('ai_prompt_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('purpose'); // DIAGNOSIS, SUGGESTION, FLEET_INSIGHT
            $table->longText('system_prompt');
            $table->json('variables')->nullable(); // ["name", "vehicle_brand"]
            $table->string('version')->default('1.0.0');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 7. AI Prompt Executions (Audit)
        Schema::create('ai_prompt_executions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prompt_template_id')->constrained('ai_prompt_templates');
            $table->foreignId('conversation_id')->nullable()->constrained('ai_conversations');
            $table->foreignId('model_id')->constrained('ai_models');
            $table->string('execution_status'); // SUCCESS, FAILED
            $table->integer('latency_ms')->nullable();
            $table->integer('total_tokens')->nullable();
            $table->decimal('estimated_cost', 10, 6)->nullable();
            $table->timestamps();
        });

        // 8. AI Business Contexts
        Schema::create('ai_contexts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('ai_conversations')->onDelete('cascade');
            $table->string('entity_type'); // Vehicle, RepairOrder, Order, Fleet
            $table->unsignedBigInteger('entity_id');
            $table->text('summary')->nullable(); // Static snapshot for prompt injection
            $table->timestamps();
        });

        // 9. AI Memory
        Schema::create('ai_memories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('memory_type'); // FACT, PREFERENCE, HISTORY
            $table->text('content');
            $table->integer('importance')->default(1); // 1-10
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_memories');
        Schema::dropIfExists('ai_contexts');
        Schema::dropIfExists('ai_prompt_executions');
        Schema::dropIfExists('ai_prompt_templates');
    }
};
