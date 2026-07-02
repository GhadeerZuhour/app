<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('account_month_closings', function (Blueprint $table) {
            // Add columns only if they don't exist (safety)
            if (!Schema::hasColumn('account_month_closings', 'tenant_id')) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
            }

            if (!Schema::hasColumn('account_month_closings', 'account_id')) {
                $table->unsignedBigInteger('account_id')->nullable()->after('tenant_id');
            }

            if (!Schema::hasColumn('account_month_closings', 'period')) {
                $table->string('period', 7)->nullable()->after('account_id')->index();
            }

            foreach (['opening','total_in','total_out','closing'] as $col) {
                if (!Schema::hasColumn('account_month_closings', $col)) {
                    $table->decimal($col, 15, 2)->default(0);
                }
            }

            if (!Schema::hasColumn('account_month_closings', 'archived_at')) {
                $table->timestamp('archived_at')->nullable();
            }
        });

        // Add indexes/constraints in a separate call (Postgres plays nicer this way)
        Schema::table('account_month_closings', function (Blueprint $table) {
            // If you want tenant_id to reference tenants/users, do it after you confirm the correct table.
            $table->foreign('tenant_id')->references('id')->on('users')->cascadeOnDelete();

            // account_id foreign key (assuming accounts table exists)
            $table->foreign('account_id')->references('id')->on('accounts')->cascadeOnDelete();

            // unique constraint
            $table->unique(['tenant_id', 'account_id', 'period'], 'amc_tenant_account_period_unique');
        });
    }

    public function down(): void
    {
        Schema::table('account_month_closings', function (Blueprint $table) {
            $table->dropUnique('amc_tenant_account_period_unique');

            // Drop columns (optional)
            $table->dropColumn([
                'tenant_id','account_id','period',
                'opening','total_in','total_out','closing',
                'archived_at'
            ]);
        });
    }
};
