<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('subscription_plans', 'slug')) {
            return;
        }

        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropUnique('subscription_plans_slug_unique');
        });

        Schema::table('subscription_plans', function (Blueprint $table) {
            $columnsToDrop = ['slug', 'monthly_price', 'yearly_price', 'max_branches', 'max_employees', 'max_vehicles_per_month', 'max_storage_gb', 'features', 'is_active'];
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('subscription_plans', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            if (! Schema::hasColumn('subscription_plans', 'slug')) {
                $table->string('slug')->unique()->after('name');
            }
            if (! Schema::hasColumn('subscription_plans', 'monthly_price')) {
                $table->decimal('monthly_price', 10, 2)->default(0)->after('slug');
            }
            if (! Schema::hasColumn('subscription_plans', 'yearly_price')) {
                $table->decimal('yearly_price', 10, 2)->nullable()->after('monthly_price');
            }
            if (! Schema::hasColumn('subscription_plans', 'max_branches')) {
                $table->integer('max_branches')->default(1)->after('yearly_price');
            }
            if (! Schema::hasColumn('subscription_plans', 'max_employees')) {
                $table->integer('max_employees')->default(5)->after('max_branches');
            }
            if (! Schema::hasColumn('subscription_plans', 'max_vehicles_per_month')) {
                $table->integer('max_vehicles_per_month')->nullable()->after('max_employees');
            }
            if (! Schema::hasColumn('subscription_plans', 'max_storage_gb')) {
                $table->integer('max_storage_gb')->nullable()->after('max_vehicles_per_month');
            }
            if (! Schema::hasColumn('subscription_plans', 'features')) {
                $table->json('features')->nullable()->after('max_storage_gb');
            }
            if (! Schema::hasColumn('subscription_plans', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('features');
            }
        });
    }
};
