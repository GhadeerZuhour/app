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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();

            // 🔑 Tenant (Subscriber)
            $table->foreignId('tenant_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Account info
            $table->string('name'); // Cash, Main Bank, Savings, etc.

            // Bank relation (nullable for cash accounts)
            $table->foreignId('bank_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Currency
            $table->foreignId('currency_id')
                ->constrained()
                ->cascadeOnDelete();

            // Account type (cash / bank / check)
            $table->enum('type', ['cash', 'bank', 'check']);

            // Status
            $table->boolean('is_active')->default(true);

            // Balance (calculated or stored)
            $table->decimal('balance', 15, 2)->default(0);

            $table->timestamps();

            // Performance index
            $table->index(['tenant_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
