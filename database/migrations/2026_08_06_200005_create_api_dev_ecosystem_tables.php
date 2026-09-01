<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 22. API Versions
        Schema::create('api_versions', function (Blueprint $table) {
            $table->id();
            $table->string('version_name'); // v1, v2
            $table->date('release_date');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 24. Developer Applications
        Schema::create('developer_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('developer_id')->constrained('users')->onDelete('cascade');
            $table->string('app_name');
            $table->text('description')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // 25. Developer Subscriptions
        Schema::create('developer_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('developer_applications')->onDelete('cascade');
            $table->string('plan_name'); // FREE, PRO, PARTNER
            $table->integer('daily_limit')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('developer_subscriptions');
        Schema::dropIfExists('developer_applications');
        Schema::dropIfExists('api_versions');
    }
};
