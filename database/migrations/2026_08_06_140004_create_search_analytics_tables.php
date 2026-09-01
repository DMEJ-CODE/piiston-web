<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 13. Search Analytics (Summary)
        Schema::create('search_analytics_summary', function (Blueprint $table) {
            $table->id();
            $table->string('keyword');
            $table->foreignId('country_id')->nullable()->constrained('countries');
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->integer('search_count')->default(0);
            $table->integer('result_count')->default(0);
            $table->integer('click_count')->default(0);
            $table->timestamps();
        });

        // 14. Search Sessions
        Schema::create('search_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });

        // 15. Search Result Clicks
        Schema::create('search_result_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('search_sessions')->onDelete('cascade');
            $table->foreignId('search_index_id')->constrained('search_indexes')->onDelete('cascade');
            $table->integer('position'); // Order in results
            $table->timestamp('clicked_at')->useCurrent();
        });

        // 16. Search Keywords
        Schema::create('search_keywords', function (Blueprint $table) {
            $table->id();
            $table->string('keyword')->unique();
            $table->string('language_code', 5)->default('en');
            $table->string('normalized_keyword'); // lowercase, no special chars
            $table->integer('frequency')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_keywords');
        Schema::dropIfExists('search_result_clicks');
        Schema::dropIfExists('search_sessions');
        Schema::dropIfExists('search_analytics_summary');
    }
};
