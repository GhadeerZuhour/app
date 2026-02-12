<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tenant_meta', function (Blueprint $table) {
            $table->uuid('tenant_id')->primary();

            $table->string('name');
            $table->string('type')->nullable();

            $table->unsignedBigInteger('owner_user_id')->nullable();
            $table->string('owner_email')->nullable();

            $table->enum('subscription_period', ['monthly','quarterly', 'semiannual', 'yearly'])->default('monthly');
            $table->date('subscription_start_at')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();

            $table->index(['name']);
            $table->index(['owner_email']);
            $table->index(['is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_meta');
    }
};
