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
        // Demo databases created during earlier development may already contain
        // this table while their migrations table is missing this migration.
        // Keep the migration idempotent so Laravel can synchronize its history
        // and continue with the migrations that add the remaining columns.
        if (! Schema::hasTable('account_month_closings')) {
            Schema::create('account_month_closings', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_month_closings');
    }
};
