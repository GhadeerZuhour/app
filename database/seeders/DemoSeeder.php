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

            $cash = Account::updateOrCreate(
                ['tenant_id' => $tenant->id, 'name' => 'Main Cash'],
                [
                    'currency_id' => $currency->id,
                    'type' => 'cash',
                    'status' => 'active',
                    'balance' => 12350,
                    'is_active' => true,
                ]
            );

            $bankAccount = Account::updateOrCreate(
                ['tenant_id' => $tenant->id, 'name' => 'Operating Bank Account'],
                [
                    'bank_id' => $bank->id,
                    'currency_id' => $currency->id,
                    'type' => 'bank',
                    'status' => 'active',
                    'balance' => 37600,
                    'is_active' => true,
                ]
            );

            $month = now()->startOfMonth()->toDateString();

            ZeroBalance::updateOrCreate(
                ['tenant_id' => $tenant->id, 'account_id' => $cash->id, 'month' => $month],
                ['zero_amount' => 8500]
            );

            ZeroBalance::updateOrCreate(
                ['tenant_id' => $tenant->id, 'account_id' => $bankAccount->id, 'month' => $month],
                ['zero_amount' => 32000]
            );

            $ownerId = $tenant->meta?->owner_user_id;

            $this->createEntry($tenant->id, $cash->id, $ownerId, 'cash', 'in', 6200, now()->subDays(10), 'Cash sales', 'SALE-1001');
            $this->createEntry($tenant->id, $cash->id, $ownerId, 'cash', 'out', 2350, now()->subDays(8), 'Supplier payment', 'EXP-2001');
            $this->createEntry($tenant->id, $bankAccount->id, $ownerId, 'bank', 'in', 9800, now()->subDays(6), 'Customer transfer', 'TR-3001');
            $this->createEntry($tenant->id, $bankAccount->id, $ownerId, 'bank', 'out', 4200, now()->subDays(4), 'Monthly operating expenses', 'EXP-2002');
            $this->createEntry($tenant->id, $cash->id, $ownerId, 'cash', 'in', 1800, now()->subDays(2), 'Retail receipts', 'SALE-1002');

            $checkEntry = Entry::updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'account_id' => $bankAccount->id,
                    'payment_method' => 'check',
                    'reference_no' => 'CHK-BATCH-01',
                ],
                [
                    'user_id' => $ownerId,
                    'direction' => 'in',
                    'entry_date' => now()->subDay()->toDateString(),
                    'total_amount' => 7500,
                    'description' => 'Three scheduled customer checks',
                ]
            );

            foreach ([
                ['number' => 'CHK-1001', 'months' => 0],
                ['number' => 'CHK-1002', 'months' => 1],
                ['number' => 'CHK-1003', 'months' => 2],
            ] as $item) {
                CheckDetails::updateOrCreate(
                    ['entry_id' => $checkEntry->id, 'check_number' => $item['number']],
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
        string $direction,
        float $amount,
        Carbon $date,
        string $description,
        string $reference
    ): void {
        Entry::updateOrCreate(
            [
                'tenant_id' => $tenantId,
                'reference_no' => $reference,
            ],
            [
                'account_id' => $accountId,
                'user_id' => $userId,
                'payment_method' => $method,
                'direction' => $direction,
                'entry_date' => $date->toDateString(),
                'total_amount' => $amount,
                'description' => $description,
            ]
        );
    }
}
