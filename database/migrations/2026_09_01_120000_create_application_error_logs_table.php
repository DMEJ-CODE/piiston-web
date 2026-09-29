<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_error_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('exception_class');
            $table->text('message');
            $table->text('file')->nullable();
            $table->unsignedInteger('line')->nullable();
            $table->string('method', 10)->nullable();
            $table->text('url')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('trace')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_error_logs');
    }
};
