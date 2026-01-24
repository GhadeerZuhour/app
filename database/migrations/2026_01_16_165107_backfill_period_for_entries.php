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
        DB::table('entries')
            ->whereNull('period')
            ->orderBy('id')
            ->chunkById(500, function ($rows) {
                foreach ($rows as $row) {
                    if ($row->entry_date) {
                        DB::table('entries')->where('id', $row->id)->update([
                            'period' => Carbon::parse($row->entry_date)->format('Y-m'),
                        ]);
                    }
                }
            });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
