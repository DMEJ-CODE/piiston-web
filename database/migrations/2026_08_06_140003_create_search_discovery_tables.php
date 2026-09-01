<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 10. Search Filters
        Schema::create('search_filters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('search_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('field');
            $table->string('data_type'); // range, list, boolean, geo
            $table->string('operator')->default('=');
            $table->timestamps();
        });

        // 11. Search Rankings
        Schema::create('search_rankings', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id');
            $table->decimal('ranking_score', 12, 4)->default(0);
            $table->string('algorithm_version')->default('1.0');
            $table->timestamps();
        });

        // 12. Autocomplete Suggestions
        Schema::create('autocomplete_suggestions', function (Blueprint $table) {
            $table->id();
            $table->string('keyword');
            $table->integer('frequency')->default(1);
            $table->string('language_code', 5)->default('en');
            $table->foreignId('country_id')->nullable()->constrained('countries');
            $table->timestamps();
            $table->unique(['keyword', 'language_code', 'country_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('autocomplete_suggestions');
        Schema::dropIfExists('search_rankings');
        Schema::dropIfExists('search_filters');
    }
};
