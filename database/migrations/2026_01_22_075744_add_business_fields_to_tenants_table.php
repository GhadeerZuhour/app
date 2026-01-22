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
        Schema::table('tenants', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('business_name')->nullable();
            $table->string('business_type')->nullable();
            $table->string('business_phone')->nullable();
            $table->string('business_address')->nullable();

            $table->enum('subscription_period', ['monthly', 'yearly'])->default('monthly');
            $table->date('subscription_ends_at')->nullable();
            $table->boolean('is_active')->default(true);

            $table->index(['is_active', 'subscription_ends_at']);
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
