<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->string('notchpay_reference')->nullable()->unique()->after('auto_renew');
            $table->text('checkout_url')->nullable()->after('notchpay_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->dropUnique(['notchpay_reference']);
            $table->dropColumn(['notchpay_reference', 'checkout_url']);
        });
    }
};
