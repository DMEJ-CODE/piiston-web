<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('garage_subscriptions', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->foreignId('plan_id')->nullable()->change()->constrained('garage_subscription_plans');
        });
    }

    public function down(): void
    {
        Schema::table('garage_subscriptions', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->foreignId('plan_id')->nullable()->change()->constrained('subscription_plans');
        });
    }
};
