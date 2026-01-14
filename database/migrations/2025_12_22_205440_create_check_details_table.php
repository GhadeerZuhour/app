<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('check_details', function (Blueprint $table) {

            $table->id();

            $table->foreignId('entry_id')
                ->constrained()
                ->cascadeOnDelete();

            // Grid fields (from your screenshot)
            $table->string('customer')->nullable();       // الزبون
            $table->string('item')->nullable();           // البند
            $table->string('check_number');               // رقم الشيك
            $table->string('bank_name');                  // من البنك
            $table->string('account_number')->nullable(); // رقم الحساب
            $table->string('branch_number')->nullable();  // رقم الفرع

            $table->decimal('amount', 15, 2);

            $table->enum('direction', ['in', 'out']);     // داخل / خارج

            $table->date('check_date');

            $table->string('attachment')->nullable();     // file path

            $table->timestamps();

            $table->index(['check_number', 'check_date']);
        });

    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_details');
    }
};
