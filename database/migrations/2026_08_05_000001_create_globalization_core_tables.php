<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Currencies
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 10)->unique();
            $table->string('symbol', 10);
            $table->integer('decimal_places')->default(2);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 2. Languages
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 5)->unique();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 3. Timezones
        Schema::create('timezones', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('utc_offset', 10);
            $table->timestamps();
        });

        // 4. Countries
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('iso_code', 5)->unique();
            $table->string('phone_code', 10);
            $table->foreignId('currency_id')->nullable()->constrained('currencies');
            $table->foreignId('default_language_id')->nullable()->constrained('languages');
            $table->foreignId('timezone_id')->nullable()->constrained('timezones');
            $table->string('flag')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
        Schema::dropIfExists('timezones');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('currencies');
    }
};
