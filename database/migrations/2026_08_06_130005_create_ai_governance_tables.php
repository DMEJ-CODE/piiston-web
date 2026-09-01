<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 17. AI Fraud Detection Cases
        Schema::create('ai_fraud_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('risk_score')->default(0); // 0-100
            $table->text('reason')->nullable();
            $table->string('status')->default('OPEN'); // OPEN, INVESTIGATING, RESOLVED
            $table->timestamps();
        });

        // 18. AI Feedback
        Schema::create('ai_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('ai_conversations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        // 19. AI Usage Logs (Cost Tracking)
        Schema::create('ai_usage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assistant_id')->nullable()->constrained('ai_assistants');
            $table->foreignId('model_id')->constrained('ai_models');
            $table->integer('tokens_used')->default(0);
            $table->decimal('estimated_cost', 10, 6)->default(0);
            $table->integer('execution_time_ms')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_usage_logs');
        Schema::dropIfExists('ai_feedback');
        Schema::dropIfExists('ai_fraud_cases');
    }
};
