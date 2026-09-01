<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. DMS Documents
        Schema::create('dms_documents', function (Blueprint $table) {
            $table->id();
            $table->string('owner_type');
            $table->unsignedBigInteger('owner_id');
            $table->foreignId('category_id')->constrained('dms_categories');
            $table->foreignId('type_id')->constrained('dms_types');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('mime_type');
            $table->bigInteger('file_size');
            $table->string('checksum')->nullable();
            $table->string('visibility')->default('private');
            $table->string('status')->default('active');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index(['owner_type', 'owner_id']);
        });

        // 3. DMS Versions
        Schema::create('dms_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('dms_documents')->onDelete('cascade');
            $table->integer('version')->default(1);
            $table->string('file_path');
            $table->string('checksum')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // 17. DMS Media Files
        Schema::create('dms_media_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('dms_documents')->onDelete('cascade');
            $table->string('media_type');
            $table->string('thumbnail')->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->string('resolution')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dms_media_files');
        Schema::dropIfExists('dms_versions');
        Schema::dropIfExists('dms_documents');
    }
};
