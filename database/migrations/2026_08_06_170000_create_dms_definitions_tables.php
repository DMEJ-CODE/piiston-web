<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. DMS Categories
        Schema::create('dms_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. DMS Types
        Schema::create('dms_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('dms_categories')->onDelete('cascade');
            $table->string('name');
            $table->boolean('requires_approval')->default(false);
            $table->boolean('requires_signature')->default(false);
            $table->boolean('expires')->default(false);
            $table->timestamps();
        });

        // 3. DMS Templates
        Schema::create('dms_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('category');
            $table->string('template_file');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 4. DMS Retentions
        Schema::create('dms_retentions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained('dms_types')->onDelete('cascade');
            $table->integer('retention_period_days');
            $table->boolean('auto_delete')->default(false);
            $table->boolean('archive_after')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dms_retentions');
        Schema::dropIfExists('dms_templates');
        Schema::dropIfExists('dms_types');
        Schema::dropIfExists('dms_categories');
    }
};
