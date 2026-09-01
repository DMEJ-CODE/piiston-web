<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 3. Spare Part Categories
        Schema::create('part_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('part_categories')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 4. Spare Part Brands
        Schema::create('part_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('country_origin')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 1. Seller Profiles
        Schema::create('seller_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('country_id')->constrained('countries');
            $table->string('business_name');
            $table->string('business_type'); // PART_STORE, WHOLESALER, etc.
            $table->string('registration_number')->nullable();
            $table->string('tax_number')->nullable();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->foreignId('address_id')->nullable()->constrained('addresses');
            $table->string('verification_status')->default('pending');
            $table->decimal('rating', 3, 2)->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 2. Seller Branches
        Schema::create('seller_branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('seller_profiles')->onDelete('cascade');
            $table->foreignId('address_id')->nullable()->constrained('addresses');
            $table->foreignId('manager_id')->nullable()->constrained('users');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_branches');
        Schema::dropIfExists('seller_profiles');
        Schema::dropIfExists('part_brands');
        Schema::dropIfExists('part_categories');
    }
};
