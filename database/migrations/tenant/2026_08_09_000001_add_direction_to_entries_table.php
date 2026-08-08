<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('entries', 'direction')) {
            Schema::table('entries', function (Blueprint $table) {
                $table->string('direction', 10)->default('in')->after('payment_method')->index();
            });
