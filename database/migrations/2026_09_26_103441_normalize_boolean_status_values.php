<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The garage tables store "status" as a boolean column, but some write paths
     * stored string values such as 'ACTIVE' or 'active' instead. Queries that
     * filter on the boolean value (status = 1) therefore silently skipped those
     * rows, hiding garages from search and nearby listings.
     *
     * This normalises every non-boolean value on those columns back to a real
     * boolean so the stored representation matches the column definition.
     *
     * @var list<string>
     */
    private const TABLES = [
        'garage_companies',
        'garage_branches',
    ];

    /**
     * Values that mean "enabled" and must collapse to true.
     *
     * @var list<string>
     */
    private const TRUTHY = [
        'active', 'Active', 'ACTIVE',
        'enabled', 'Enabled', 'ENABLED',
        'true', 'yes', 'on', 'published', 'visible',
    ];

    /**
     * Values that mean "disabled" and must collapse to false.
     *
     * @var list<string>
     */
    private const FALSY = [
        'inactive', 'Inactive', 'INACTIVE',
        'disabled', 'Disabled', 'DISABLED',
        'false', 'no', 'off', 'hidden', 'draft', 'archived',
    ];

    public function up(): void
    {
        foreach (self::TABLES as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'status')) {
                continue;
            }

            DB::table($table)->whereIn('status', self::TRUTHY)->update(['status' => true]);
            DB::table($table)->whereIn('status', self::FALSY)->update(['status' => false]);
        }
    }

    public function down(): void
    {
        // The previous representation was inconsistent, so there is no single
        // value to restore. The normalised booleans are the intended state.
    }
};
