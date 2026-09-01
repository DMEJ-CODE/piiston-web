<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 3. Mechanic Employments
        Schema::create('mechanic_employments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mechanic_id')->constrained('mechanic_profiles')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('garage_branches')->onDelete('cascade');
            $table->string('position')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('employment_status')->default('active');
            $table->timestamps();
        });

        // 8. Mechanic Availabilities
        Schema::create('mechanic_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mechanic_id')->constrained('mechanic_profiles')->onDelete('cascade');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('status')->default('AVAILABLE');
            $table->timestamps();
        });

        // 9. Mechanic Location
        Schema::create('mechanic_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mechanic_id')->constrained('mechanic_profiles')->onDelete('cascade');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->integer('radius_km')->default(10);
            $table->timestamp('last_updated');
            $table->timestamps();
        });

        // 10. Mobile Service Areas
        Schema::create('mechanic_service_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mechanic_id')->constrained('mechanic_profiles')->onDelete('cascade');
            $table->foreignId('city_id')->constrained('cities');
            $table->integer('maximum_distance')->default(20);
            $table->timestamps();
        });

        // 11. Mechanic Tools
        Schema::create('mechanic_tools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mechanic_id')->constrained('mechanic_profiles')->onDelete('cascade');
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('condition')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mechanic_tools');
        Schema::dropIfExists('mechanic_service_areas');
        Schema::dropIfExists('mechanic_locations');
        Schema::dropIfExists('mechanic_availabilities');
        Schema::dropIfExists('mechanic_employments');
    }
};
