<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garage_subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // FREE, BASIC, PRO, ENTERPRISE
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->decimal('monthly_price', 10, 2)->default(0);
            $table->decimal('yearly_price', 10, 2)->nullable();
            $table->integer('max_branches')->default(1);
            $table->integer('max_employees')->default(5);
            $table->integer('max_vehicles_per_month')->nullable();
            $table->integer('max_storage_gb')->nullable();
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garage_subscription_plans');
    }
};
