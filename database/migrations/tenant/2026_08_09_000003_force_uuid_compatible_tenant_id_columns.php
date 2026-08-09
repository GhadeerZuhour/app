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

            DB::statement(sprintf(
                'ALTER TABLE "%s" ALTER COLUMN "tenant_id" TYPE varchar(36) USING "tenant_id"::text',
                str_replace('"', '""', $table)
            ));
        }

        $accountsType = DB::selectOne(<<<'SQL'
            SELECT data_type
            FROM information_schema.columns
            WHERE table_schema = current_schema()
              AND table_name = 'accounts'
              AND column_name = 'tenant_id'
        SQL);

        if ($accountsType && ! in_array($accountsType->data_type, ['character varying', 'text', 'uuid'], true)) {
            throw new RuntimeException('accounts.tenant_id is still not UUID-compatible after migration.');
        }
    }

    public function down(): void
    {
        // Non-destructive by design: UUID tenant IDs cannot safely be converted back to bigint.
    }
};
