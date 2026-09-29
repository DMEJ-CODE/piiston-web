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
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->string('notchpay_reference')->nullable()->unique()->after('reference');
            $table->text('checkout_url')->nullable()->after('notchpay_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->dropUnique(['notchpay_reference']);
            $table->dropColumn(['notchpay_reference', 'checkout_url']);
        });
    }
};
