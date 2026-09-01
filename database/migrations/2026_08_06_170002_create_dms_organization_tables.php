<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 4. DMS Folders
        Schema::create('dms_folders', function (Blueprint $table) {
            $table->id();
            $table->string('owner_type');
            $table->unsignedBigInteger('owner_id');
            $table->string('name');
            $table->foreignId('parent_folder_id')->nullable()->constrained('dms_folders')->onDelete('cascade');
            $table->timestamps();
        });

        // 5. DMS Tags
        Schema::create('dms_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('color')->nullable();
            $table->timestamps();
        });

        Schema::create('dms_tag_assignments', function (Blueprint $table) {
            $table->foreignId('document_id')->constrained('dms_documents')->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('dms_tags')->onDelete('cascade');
            $table->primary(['document_id', 'tag_id']);
        });

        // Update documents to include folder_id
        Schema::table('dms_documents', function (Blueprint $table) {
            $table->foreignId('folder_id')->nullable()->after('type_id')->constrained('dms_folders')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('dms_documents', function (Blueprint $table) {
            $table->dropConstrainedForeignId('folder_id');
        });
        Schema::dropIfExists('dms_tag_assignments');
        Schema::dropIfExists('dms_tags');
        Schema::dropIfExists('dms_folders');
    }
};
