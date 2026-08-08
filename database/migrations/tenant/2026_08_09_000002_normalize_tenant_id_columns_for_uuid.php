<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        foreach ([
            'accounts',
            'entries',
            'zero_balances',
            'account_month_closings',
        ] as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'tenant_id')) {
                continue;
            }

            $column = DB::selectOne(
                <<<'SQL'
                    SELECT data_type, character_maximum_length
                    FROM information_schema.columns
                    WHERE table_schema = current_schema()
                      AND table_name = ?
                      AND column_name = 'tenant_id'
                SQL,
                [$table]
            );

            if (! $column) {
                continue;
            }

            if (in_array($column->data_type, ['character varying', 'text', 'uuid'], true)) {
                continue;
            }

            DB::statement(sprintf(
                'ALTER TABLE "%s" ALTER COLUMN "tenant_id" TYPE varchar(36) USING "tenant_id"::text',
                str_replace('"', '""', $table)
            ));
        }
    }

    public function down(): void
    {
        // Intentionally non-destructive. Once UUID tenant IDs are stored,
        // converting this column back to bigint would lose or invalidate data.
    }
};
