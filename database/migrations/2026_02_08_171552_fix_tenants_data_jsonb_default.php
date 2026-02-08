<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver !== 'pgsql') {
            DB::statement("UPDATE tenants SET data = '{}' WHERE data IS NULL");

            return;
        }

        DB::statement("UPDATE tenants SET data = '{}'::jsonb WHERE data IS NULL");
        DB::statement("ALTER TABLE tenants ALTER COLUMN data SET DEFAULT '{}'::jsonb");
        DB::statement('ALTER TABLE tenants ALTER COLUMN data SET NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('ALTER TABLE tenants ALTER COLUMN data DROP NOT NULL');
        DB::statement('ALTER TABLE tenants ALTER COLUMN data DROP DEFAULT');
    }
};
