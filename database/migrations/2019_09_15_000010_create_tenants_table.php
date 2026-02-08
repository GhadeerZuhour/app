<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // IMPORTANT: jsonb + default {} + NOT NULL (Postgres)
            $table->jsonb('data')->default('{}')->nullable(false);

            // Optional: if you want also tenancy_db_name as a real column, keep it separate
            // (stancl sometimes stores it in data, sometimes as column depending on your edits)
            // $table->string('tenancy_db_name')->nullable()->unique();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
}
