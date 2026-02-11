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
        Schema::create('tenants', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // ✅ Stancl attributes stored as separate JSONB columns
            $table->jsonb('profile')->default(DB::raw("'{}'::jsonb"))->nullable(false);
            $table->jsonb('owner')->default(DB::raw("'{}'::jsonb"))->nullable(false);
            $table->jsonb('subscription')->default(DB::raw("'{}'::jsonb"))->nullable(false);

            // ✅ tenancy database name
            $table->string('tenancy_db_name')->nullable()->unique();

            // ✅ optional (if you use domains table separately, no need here)
            $table->timestamps();
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            //
        });
    }
};
