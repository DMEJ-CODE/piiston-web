<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 13. DMS Approvals
        Schema::create('dms_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('dms_documents')->onDelete('cascade');
            $table->foreignId('approved_by')->constrained('users');
            $table->string('status')->default('PENDING');
            $table->text('notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        // 15. DMS Verifications
        Schema::create('dms_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('dms_documents')->onDelete('cascade');
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->string('status')->default('pending');
            $table->text('reason')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        // 14. DMS Signatures
        Schema::create('dms_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('dms_documents')->onDelete('cascade');
            $table->string('signature_type');
            $table->foreignId('signed_by')->constrained('users');
            $table->timestamp('signed_at')->useCurrent();
            $table->timestamps();
        });

        // 22. DMS Seals
        Schema::create('dms_seals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('dms_documents')->onDelete('cascade');
            $table->string('seal_hash')->unique();
            $table->foreignId('issued_by')->constrained('users');
            $table->timestamp('issued_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dms_seals');
        Schema::dropIfExists('dms_signatures');
        Schema::dropIfExists('dms_verifications');
        Schema::dropIfExists('dms_approvals');
    }
};
