<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 7. Recommendations
        Schema::create('platform_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('recommendation_type'); // PERSONALIZED, SIMILAR_ITEMS, TRENDING
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id');
            $table->decimal('score', 8, 4)->default(0);
            $table->text('reason')->nullable();
            $table->timestamps();
        });

        // 8. Recommendation Rules
        Schema::create('recommendation_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('rule_type'); // COLLABORATIVE_FILTERING, CONTENT_BASED, POPULARITY
            $table->json('configuration')->nullable();
            $table->integer('priority')->default(1);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 9. Trending Items
        Schema::create('trending_items', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id');
            $table->foreignId('country_id')->nullable()->constrained('countries');
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->decimal('score', 10, 2)->default(0);
            $table->string('period')->default('weekly'); // daily, weekly, monthly
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trending_items');
        Schema::dropIfExists('recommendation_rules');
        Schema::dropIfExists('platform_recommendations');
    }
};
