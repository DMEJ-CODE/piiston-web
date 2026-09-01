<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Garage Companies
        Schema::create('garage_companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users');
            $table->foreignId('country_id')->constrained('countries');
            $table->string('name');
            $table->string('legal_name')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->string('verification_status')->default('pending');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 2. Garage Branches
        Schema::create('garage_branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('garage_companies')->onDelete('cascade');
            $table->foreignId('address_id')->nullable()->constrained('addresses');
            $table->foreignId('manager_id')->nullable()->constrained('users');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->date('opening_date')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 3. Garage Departments
        Schema::create('garage_departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('garage_branches')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garage_departments');
        Schema::dropIfExists('garage_branches');
        Schema::dropIfExists('garage_companies');
    }
};
