<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Roadside Assistance (Extension of Emergency Requests)
        Schema::create('roadside_assistances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emergency_request_id')->constrained('emergency_requests')->onDelete('cascade');
            $table->foreignId('mechanic_id')->nullable()->constrained('mechanic_profiles');
            $table->foreignId('garage_id')->nullable()->constrained('garage_companies');
            $table->timestamp('estimated_arrival_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // 15. Delivery Zones
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('seller_profiles')->onDelete('cascade');
            $table->foreignId('country_id')->constrained('countries');
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->integer('radius_km')->nullable();
            $table->decimal('delivery_fee', 12, 2)->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // Delivery Coverage
        Schema::create('delivery_coverages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_zone_id')->constrained('delivery_zones')->onDelete('cascade');
            $table->string('district')->nullable();
            $table->string('postal_code')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_coverages');
        Schema::dropIfExists('delivery_zones');
        Schema::dropIfExists('roadside_assistances');
    }
};
