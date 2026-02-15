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
        Schema::create('support_messages', function (Blueprint $table) {
            $table->id();
            $table->uuid('ticket_id')->index();
            $table->foreign('ticket_id')->references('id')->on('support_tickets')->cascadeOnDelete();

             $table->string('sender_type')->default('tenant_user')->after('ticket_id'); // admin|tenant_user

            // admin sender
            $table->foreignId('admin_user_id')->nullable()->after('sender_type')
                ->constrained('users')->nullOnDelete();

            // tenant sender (no FK)
            $table->unsignedBigInteger('tenant_user_id')->nullable()->after('admin_user_id');

            // snapshot sender info
            $table->string('sender_name')->nullable()->after('tenant_user_id');
            $table->string('sender_email')->nullable()->after('sender_name');
            $table->text('message');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_messages');
    }
};
