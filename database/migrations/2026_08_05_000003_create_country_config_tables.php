<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 8. Country Languages (Many-to-Many)
        Schema::create('country_languages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // 9. Country Currencies (Many-to-Many)
        Schema::create('country_currencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            $table->foreignId('currency_id')->constrained('currencies')->onDelete('cascade');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // 10. Country Configurations
        Schema::create('country_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            $table->string('key');
            $table->text('value');
            $table->string('type')->default('string'); // string, boolean, integer, json
            $table->timestamps();
            $table->unique(['country_id', 'key']);
        });

        // 11. Phone Country Codes
        Schema::create('phone_country_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            $table->string('code', 10);
            $table->string('format')->nullable();
            $table->timestamps();
        });

        // 12. Tax Configurations
        Schema::create('tax_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            $table->string('name');
            $table->decimal('percentage', 5, 2);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 13. Payment Providers
        Schema::create('payment_providers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            $table->string('name');
            $table->string('type'); // mobile_money, bank_transfer, card, etc.
            $table->json('api_configuration')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 14. Document Types
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            $table->string('name');
            $table->string('required_for'); // user, mechanic, seller, garage
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_types');
        Schema::dropIfExists('payment_providers');
        Schema::dropIfExists('tax_configurations');
        Schema::dropIfExists('phone_country_codes');
        Schema::dropIfExists('country_configurations');
        Schema::dropIfExists('country_currencies');
        Schema::dropIfExists('country_languages');
    }
};
