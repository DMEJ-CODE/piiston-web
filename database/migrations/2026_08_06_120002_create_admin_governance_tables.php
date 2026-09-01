<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 9. Moderation Center
        Schema::create('moderation_cases', function (Blueprint $table) {
            $table->id();
            $table->string('reported_entity'); // User, Message, SparePart, Review
            $table->unsignedBigInteger('entity_id');
            $table->foreignId('reported_by')->constrained('users');
            $table->text('reason');
            $table->string('priority')->default('MEDIUM');
            $table->string('status')->default('OPEN');
            $table->foreignId('assigned_admin_id')->nullable()->constrained('administrators');
            $table->timestamps();
        });

        // 23. Fraud Detection
        Schema::create('fraud_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('case_type'); // Payment, MultipleAccounts, SuspiciousActivity
            $table->string('risk_level')->default('LOW');
            $table->string('status')->default('OPEN');
            $table->timestamps();
        });

        // 16. Admin Audit Log
        Schema::create('admin_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrator_id')->constrained('administrators');
            $table->string('action'); // SUSPEND_USER, APPROVE_GARAGE, etc.
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('device_info')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_audit_logs');
        Schema::dropIfExists('fraud_cases');
        Schema::dropIfExists('moderation_cases');
    }
};
