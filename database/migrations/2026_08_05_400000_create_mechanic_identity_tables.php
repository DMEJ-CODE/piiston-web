<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 2. Mechanic Types
        Schema::create('mechanic_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // INDEPENDENT, GARAGE_EMPLOYEE, etc.
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 1. Mechanic Profiles
        Schema::create('mechanic_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('country_id')->constrained('countries');
            $table->foreignId('type_id')->nullable()->constrained('mechanic_types');
            $table->string('professional_title')->nullable();
            $table->text('bio')->nullable();
            $table->integer('years_of_experience')->default(0);
            $table->string('availability_status')->default('AVAILABLE');
            $table->string('profile_photo')->nullable();
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('total_reviews')->default(0);
            $table->string('verification_status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mechanic_profiles');
        Schema::dropIfExists('mechanic_types');
    }
};
