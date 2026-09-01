<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 14. Content Management (CMS)
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('faq_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('faq_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('faq_categories')->onDelete('cascade');
            $table->string('question');
            $table->text('answer');
            $table->integer('order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 12. Localization translations
        Schema::create('admin_translations', function (Blueprint $table) {
            $table->id();
            $table->string('language_code'); // fr, en
            $table->string('translation_key');
            $table->text('translation_value');
            $table->timestamps();
            $table->unique(['language_code', 'translation_key']);
        });

        // 13. Platform Announcements
        Schema::create('platform_announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->string('target_role')->default('ALL');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_announcements');
        Schema::dropIfExists('admin_translations');
        Schema::dropIfExists('faq_items');
        Schema::dropIfExists('faq_categories');
        Schema::dropIfExists('cms_pages');
    }
};
