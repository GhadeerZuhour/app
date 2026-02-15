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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('tenant_id')->index();          // tenant uuid من جدول tenants المركزي
            $table->unsignedBigInteger('requester_tenant_user_id')->nullable()->after('tenant_id');
            $table->string('requester_name')->after('requester_tenant_user_id');
            $table->string('requester_email')->after('requester_name');

            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete(); // admin (اختياري)

            $table->string('type')->default('general');  // general|renewal|bug|invoice
            $table->string('status')->default('new');    // new|in_progress|resolved
            $table->string('priority')->default('normal'); // low|normal|high

            $table->string('subject');
            $table->text('description')->nullable();

            $table->json('meta')->nullable();            // plan requested, months, payment_method, etc
            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
