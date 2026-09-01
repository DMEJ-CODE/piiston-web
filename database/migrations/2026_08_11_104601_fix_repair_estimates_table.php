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
        Schema::table('repair_estimates', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->after('id')->constrained('garage_branches')->onDelete('cascade');
            $table->decimal('labor_cost', 12, 2)->default(0)->after('subtotal');
            $table->decimal('parts_cost', 12, 2)->default(0)->after('labor_cost');
            $table->decimal('total_amount', 12, 2)->default(0)->after('discount');
            $table->date('valid_until')->nullable()->after('total_amount');
            $table->text('notes')->nullable()->after('valid_until');
        });
    }

    public function down(): void
    {
        Schema::table('repair_estimates', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['branch_id', 'labor_cost', 'parts_cost', 'total_amount', 'valid_until', 'notes']);
        });
    }
};
