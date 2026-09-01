<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 17. Geo Search Areas
        Schema::create('geo_search_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries');
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->integer('radius_km');
            $table->decimal('center_latitude', 10, 8);
            $table->decimal('center_longitude', 11, 8);
            $table->timestamps();
        });

        // 18. Search Synonyms
        Schema::create('search_synonyms', function (Blueprint $table) {
            $table->id();
            $table->string('language_code', 5)->default('en');
            $table->string('keyword');
            $table->string('synonym');
            $table->foreignId('country_id')->nullable()->constrained('countries');
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->unique(['language_code', 'keyword', 'synonym']);
        });

        // 19. Search Index Queue (Async management)
        Schema::create('search_index_queue', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id');
            $table->string('operation'); // CREATE, UPDATE, DELETE
            $table->integer('priority')->default(1);
            $table->string('status')->default('PENDING');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_index_queue');
        Schema::dropIfExists('search_synonyms');
        Schema::dropIfExists('geo_search_areas');
    }
};
