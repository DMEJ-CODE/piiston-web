<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 20. DMS Histories
        Schema::create('dms_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('dms_documents')->onDelete('cascade');
            $table->string('action');
            $table->foreignId('performed_by')->constrained('users');
            $table->timestamp('created_at')->useCurrent();
        });

        // 18. DMS Archives
        Schema::create('dms_archives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('dms_documents')->onDelete('cascade');
            $table->text('archive_reason')->nullable();
            $table->foreignId('archived_by')->constrained('users');
            $table->timestamp('archived_at')->useCurrent();
        });

        // 23. DMS Expirations
        Schema::create('dms_expirations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('dms_documents')->onDelete('cascade');
            $table->date('expiration_date');
            $table->boolean('notification_sent')->default(false);
            $table->string('status')->default('valid');
            $table->timestamps();
        });

        // 24. DMS Audit Logs
        Schema::create('dms_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('dms_documents')->onDelete('cascade');
            $table->string('action');
            $table->string('ip_address')->nullable();
            $table->string('device_info')->nullable();
            $table->timestamp('performed_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dms_audit_logs');
        Schema::dropIfExists('dms_expirations');
        Schema::dropIfExists('dms_archives');
        Schema::dropIfExists('dms_histories');
    }
};
