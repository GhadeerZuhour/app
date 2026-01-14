<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            ['code' => 'ILS', 'name' => 'Israeli Shekel'],
            ['code' => 'USD', 'name' => 'US Dollar'],
            ['code' => 'JOD', 'name' => 'Jordanian Dinar'],
            ['code' => 'EUR', 'name' => 'Euro'],
        ];

        foreach ($currencies as $currency) {
            Currency::firstOrCreate(
                ['code' => $currency['code']],
                $currency
            );
        }
    }
}
