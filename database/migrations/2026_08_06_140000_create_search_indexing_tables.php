<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Search Categories
        Schema::create('search_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Garage, Mechanic, Vehicle, etc.
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 2. Search Index (Polymorphic)
        Schema::create('search_indexes', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type'); // Model class
            $table->unsignedBigInteger('entity_id');
            $table->foreignId('category_id')->constrained('search_categories')->onDelete('cascade');
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->text('keywords')->nullable(); // Searchable tokens
            $table->foreignId('country_id')->nullable()->constrained('countries');
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('popularity_score', 10, 2)->default(0);
            $table->decimal('rating_score', 3, 2)->default(0);
            $table->decimal('search_score', 10, 2)->default(0); // Boost factor
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['entity_type', 'entity_id']);
        });

        // 3. Search Documents (Full JSON storage for heavy retrieval)
        Schema::create('search_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('search_index_id')->constrained('search_indexes')->onDelete('cascade');
            $table->string('language_code', 5)->default('en');
            $table->json('content'); // Pre-formatted result data
            $table->json('metadata')->nullable();
            $table->integer('version')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_documents');
        Schema::dropIfExists('search_indexes');
        Schema::dropIfExists('search_categories');
    }
};
