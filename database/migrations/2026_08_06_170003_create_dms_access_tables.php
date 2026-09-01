<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 11. DMS Permissions
        Schema::create('dms_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('dms_documents')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('permission');
            $table->timestamps();
        });

        // 12. DMS Shares
        Schema::create('dms_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('dms_documents')->onDelete('cascade');
            $table->foreignId('shared_by')->constrained('users');
            $table->foreignId('shared_with')->constrained('users');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        // 16. DMS Comments
        Schema::create('dms_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('dms_documents')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->text('comment');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dms_comments');
        Schema::dropIfExists('dms_shares');
        Schema::dropIfExists('dms_permissions');
    }
};
