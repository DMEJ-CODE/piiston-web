<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 4. API Requests (Global logger)
        Schema::create('api_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_client_id')->nullable()->constrained('api_clients');
            $table->string('endpoint');
            $table->string('method'); // GET, POST, etc.
            $table->string('ip_address')->nullable();
            $table->integer('response_code')->nullable();
            $table->integer('duration_ms')->nullable();
            $table->json('payload_summary')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // 5. API Responses
        Schema::create('api_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('api_requests')->onDelete('cascade');
            $table->integer('status_code');
            $table->bigInteger('payload_size');
            $table->timestamp('created_at')->useCurrent();
        });

        // 6. API Logs (Detailed errors)
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_client_id')->nullable()->constrained('api_clients');
            $table->string('action');
            $table->text('error_message')->nullable();
            $table->timestamps();
        });

        // 7. API Rate Limits
        Schema::create('api_rate_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_client_id')->constrained('api_clients')->onDelete('cascade');
            $table->integer('requests_limit');
            $table->string('period'); // minute, hour, day
            $table->integer('current_usage')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_rate_limits');
        Schema::dropIfExists('api_logs');
        Schema::dropIfExists('api_responses');
        Schema::dropIfExists('api_requests');
    }
};
