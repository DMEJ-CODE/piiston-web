<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 21. DMS OCR Results
        Schema::create('dms_ocr_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('dms_documents')->onDelete('cascade');
            $table->string('provider');
            $table->string('language_code')->default('en');
            $table->longText('raw_text')->nullable();
            $table->json('structured_data')->nullable();
            $table->integer('confidence_score')->default(0);
            $table->timestamps();
        });

        // 18. DMS Workflows
        Schema::create('dms_workflows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained('dms_types')->onDelete('cascade');
            $table->string('name');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 19. DMS Workflow Steps
        Schema::create('dms_workflow_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained('dms_workflows')->onDelete('cascade');
            $table->integer('step_order');
            $table->string('role_required');
            $table->string('action_type');
            $table->boolean('is_mandatory')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dms_workflow_steps');
        Schema::dropIfExists('dms_workflows');
        Schema::dropIfExists('dms_ocr_results');
    }
};
