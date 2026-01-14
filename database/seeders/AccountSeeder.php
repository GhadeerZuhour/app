<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\User;
use App\Models\Bank;
use App\Models\Currency;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $subscriber = User::where('role', 'subscriber')->first();

        if (! $subscriber) {
            $this->command?->warn('No subscriber user found. Run UserSeeder first.');
            return;
        }

        $bankIds = Bank::pluck('id')->toArray();
        $currencyId = Currency::where('code', 'ILS')->value('id') ?? Currency::value('id');

        if (! $currencyId) {
            $this->command?->warn('No currencies found. Run CurrencySeeder first.');
            return;
        }

        $randomBankId = !empty($bankIds) ? $bankIds[array_rand($bankIds)] : null;

        // ✅ Main Bank Account
        Account::firstOrCreate(
            [
                'tenant_id' => $subscriber->id,
                'name'      => 'Main Bank Account',
            ],
            [
                'bank_id'     => $randomBankId,
                'currency_id' => $currencyId,
                'type'        => 'bank',
                'is_active'   => true,
                'balance'     => 0,
            ]
        );

        // ✅ Cash Account
        Account::firstOrCreate(
            [
                'tenant_id' => $subscriber->id,
                'name'      => 'Cash Account',
            ],
            [
                'bank_id'     => null,
                'currency_id' => $currencyId,
                'type'        => 'cash',
                'is_active'   => true,
                'balance'     => 0,
            ]
        );
    }
}
