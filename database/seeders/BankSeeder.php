<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bank;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        $banks = [
            [
                'name'   => 'Bank of Palestine',
                'branch' => 'Main Branch',
            ],
            [
                'name'   => 'Arab Bank',
                'branch' => 'Main Branch',
            ],
            [
                'name'   => 'Cairo Amman Bank',
                'branch' => 'Main Branch',
            ],
            [
                'name'   => 'Quds Bank',
                'branch' => 'Main Branch',
            ],
            [
                'name'   => 'Islamic National Bank',
                'branch' => 'Main Branch',
            ],
        ];

        foreach ($banks as $bank) {
            Bank::firstOrCreate(
                ['name' => $bank['name'], 'branch' => $bank['branch']]
            );
        }
    }
}
