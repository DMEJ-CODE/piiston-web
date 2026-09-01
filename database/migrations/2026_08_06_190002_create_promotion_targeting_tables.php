<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 7. Audiences
        Schema::create('audiences', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('estimated_size')->default(0);
            $table->timestamps();
        });

        // 8. Audience Rules
        Schema::create('audience_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audience_id')->constrained('audiences')->onDelete('cascade');
            $table->string('field'); // vehicle_brand, city, etc.
            $table->string('operator')->default('=');
            $table->string('value');
            $table->timestamps();
        });

        // 9. Campaign Targets
        Schema::create('campaign_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->string('target_type'); // Country, City, VehicleBrand, etc.
            $table->string('target_value');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_targets');
        Schema::dropIfExists('audience_rules');
        Schema::dropIfExists('audiences');
    }
};
