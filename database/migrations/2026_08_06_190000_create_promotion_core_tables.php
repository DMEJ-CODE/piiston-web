<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Promotion Types
        Schema::create('promotion_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // DISCOUNT, FEATURED, ADVERTISEMENT, etc.
            $table->text('description')->nullable();
            $table->json('rules')->nullable();
            $table->timestamps();
        });

        // 2. Promotions
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('owner_type'); // Garage, Seller, Partner, Piiston
            $table->unsignedBigInteger('owner_id');
            $table->foreignId('promotion_type_id')->constrained('promotion_types');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();

            $table->index(['owner_type', 'owner_id']);
        });

        // 3. Campaigns
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('owner_type');
            $table->unsignedBigInteger('owner_id');
            $table->string('name');
            $table->string('objective'); // Acquisition, Visibilité, etc.
            $table->decimal('budget', 15, 2)->default(0);
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();

            $table->index(['owner_type', 'owner_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('promotion_types');
    }
};
