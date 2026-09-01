<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 14. Export Jobs
        Schema::create('export_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('export_type'); // INVOICES, ORDERS, REPAIRS
            $table->string('file_format'); // PDF, EXCEL, CSV
            $table->string('status')->default('PENDING');
            $table->string('file_path')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // 15. Report Filters
        Schema::create('report_filters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_template_id')->constrained('report_templates')->onDelete('cascade');
            $table->string('field');
            $table->string('operator');
            $table->string('value');
            $table->timestamps();
        });

        // 16. Report Shares
        Schema::create('report_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->onDelete('cascade');
            $table->foreignId('shared_with_user_id')->constrained('users')->onDelete('cascade');
            $table->string('permission')->default('VIEW');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_shares');
        Schema::dropIfExists('report_filters');
        Schema::dropIfExists('export_jobs');
    }
};
