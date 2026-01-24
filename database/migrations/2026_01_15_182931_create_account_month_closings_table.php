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
       Schema::create('account_month_closings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();

            $table->string('period', 7)->index(); // YYYY-MM

            $table->decimal('opening', 15, 2)->default(0);
            $table->decimal('total_in', 15, 2)->default(0);
            $table->decimal('total_out', 15, 2)->default(0);
            $table->decimal('closing', 15, 2)->default(0);

            $table->timestamp('archived_at')->nullable();

            $table->timestamps();

            $table->unique(['tenant_id','account_id','period']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_month_closings');
    }
};
