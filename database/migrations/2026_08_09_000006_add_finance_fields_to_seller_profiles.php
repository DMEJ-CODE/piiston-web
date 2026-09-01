<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seller_profiles', function (Blueprint $table) {
            $table->decimal('commission_rate', 5, 2)->default(10.00); // 10% default
            $table->string('payout_method')->nullable();
            $table->string('bank_account_info')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('seller_profiles', function (Blueprint $table) {
            $table->dropColumn(['commission_rate', 'payout_method', 'bank_account_info']);
        });
    }
};
