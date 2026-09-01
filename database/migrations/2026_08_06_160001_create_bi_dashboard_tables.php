<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 3. Dashboards
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            $table->string('owner_type'); // User, Garage, Fleet, Platform
            $table->unsignedBigInteger('owner_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('layout')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // 4. Dashboard Widgets
        Schema::create('dashboard_widgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dashboard_id')->constrained('dashboards')->onDelete('cascade');
            $table->string('widget_type'); // CHART, TABLE, KPI, MAP
            $table->string('title');
            $table->json('configuration')->nullable();
            $table->integer('position_x')->default(0);
            $table->integer('position_y')->default(0);
            $table->integer('width')->default(1);
            $table->integer('height')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboard_widgets');
        Schema::dropIfExists('dashboards');
    }
};
