<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('body')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('cron_expression')->nullable();
            $table->timestamp('run_at')->nullable()->index();
            $table->string('channel')->nullable();
            $table->boolean('enabled')->default(false)->index();
            $table->json('metadata')->nullable();
            $table->unsignedBigInteger('ai_model_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
