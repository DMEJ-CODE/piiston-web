<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 4. Mechanic Skills (Catalog)
        Schema::create('mechanic_skills', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 5. Mechanic Skill Assignments
        Schema::create('mechanic_skill_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mechanic_id')->constrained('mechanic_profiles')->onDelete('cascade');
            $table->foreignId('skill_id')->constrained('mechanic_skills')->onDelete('cascade');
            $table->string('experience_level')->default('beginner'); // beginner, intermediate, expert
            $table->boolean('certification')->default(false);
            $table->integer('years_practiced')->nullable();
            $table->timestamps();
        });

        // 6. Mechanic Certifications
        Schema::create('mechanic_certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mechanic_id')->constrained('mechanic_profiles')->onDelete('cascade');
            $table->string('name');
            $table->string('organization');
            $table->string('certificate_number')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('document_file')->nullable();
            $table->string('verification_status')->default('pending');
            $table->timestamps();
        });

        // 7. Mechanic Experiences
        Schema::create('mechanic_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mechanic_id')->constrained('mechanic_profiles')->onDelete('cascade');
            $table->string('company_name');
            $table->string('position');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
        });

        // 17. Mechanic Verifications
        Schema::create('mechanic_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mechanic_id')->constrained('mechanic_profiles')->onDelete('cascade');
            $table->string('document_type'); // National ID, Training Certificate, etc.
            $table->string('document_number')->nullable();
            $table->string('document_file');
            $table->string('status')->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mechanic_verifications');
        Schema::dropIfExists('mechanic_experiences');
        Schema::dropIfExists('mechanic_certifications');
        Schema::dropIfExists('mechanic_skill_assignments');
        Schema::dropIfExists('mechanic_skills');
    }
};
