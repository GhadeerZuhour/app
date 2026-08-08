<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Bank;
use App\Models\CheckDetails;
use App\Models\Currency;
use App\Models\Entry;
use App\Models\Tenant;
use App\Models\ZeroBalance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use RuntimeException;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::query()->with('meta')->first();

        if (! $tenant) {
            throw new RuntimeException('Create at least one tenant before running DemoSeeder.');
        }

        tenancy()->initialize($tenant);

        try {
            $currency = Currency::firstOrCreate(
                ['code' => 'ILS'],
                ['name' => 'Israeli New Shekel']
            );

            $bank = Bank::firstOrCreate(
                ['name' => 'Demo Bank'],
                ['branch' => 'Ramallah']
            );

            $cash = Account::firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'name' => 'Main Cash',
                ],
                [
                    'currency_id' => $currency->id,
                    'type' => 'cash',
                    'status' => 'active',
                ]
            );

            $bankAccount = Account::firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'name' => 'Operating Bank Account',
                ],
                [
                    'bank_id' => $bank->id,
                    'currency_id' => $currency->id,
                    'type' => 'bank',
                    'status' => 'active',
                ]
            );

            $month = now()->startOfMonth()->toDateString();

            ZeroBalance::updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'account_id' => $cash->id,
                    'month' => $month,
                ],
                ['zero_amount' => 8500]
            );

            ZeroBalance::updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'account_id' => $bankAccount->id,
                    'month' => $month,
                ],
                ['zero_amount' => 32000]
            );

            $ownerId = $tenant->meta?->owner_user_id;

            $this->createEntry($tenant->id, $cash->id, $ownerId, 'cash', 4200, now()->subDays(8));
            $this->createEntry($tenant->id, $cash->id, $ownerId, 'cash', 1350, now()->subDays(5));
            $this->createEntry($tenant->id, $bankAccount->id, $ownerId, 'bank', 7800, now()->subDays(4));

            $checkEntry = Entry::firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'account_id' => $bankAccount->id,
                    'payment_method' => 'check',
                    'entry_date' => now()->subDays(2)->toDateString(),
                    'total_amount' => 7500,
                ],
                ['user_id' => $ownerId]
            );

            foreach ([
                ['number' => 'CHK-1001', 'months' => 0],
                ['number' => 'CHK-1002', 'months' => 1],
                ['number' => 'CHK-1003', 'months' => 2],
            ] as $item) {
                CheckDetails::firstOrCreate(
                    [
                        'entry_id' => $checkEntry->id,
                        'check_number' => $item['number'],
                    ],
                    [
                        'customer' => 'Demo Customer',
                        'item' => 'Monthly service payment',
                        'bank_name' => 'Demo Bank',
                        'account_number' => 'DEMO-001',
                        'branch_number' => '001',
                        'amount' => 2500,
                        'direction' => 'in',
                        'check_date' => now()->addMonths($item['months'])->toDateString(),
                    ]
                );
            }

            $this->command?->info('Tazreem demo data created for tenant: '.($tenant->meta?->name ?? $tenant->id));
        } finally {
            tenancy()->end();
        }
    }

    private function createEntry(
        string $tenantId,
        int $accountId,
        ?int $userId,
        string $method,
        float $amount,
        Carbon $date
    ): void {
        Entry::firstOrCreate(
            [
                'tenant_id' => $tenantId,
                'account_id' => $accountId,
                'payment_method' => $method,
                'entry_date' => $date->toDateString(),
                'total_amount' => $amount,
            ],
            ['user_id' => $userId]
        );
    }
}
