<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Account;
use App\Services\AccountMonthService;
use Throwable;

class ArchiveMonthEnd extends Command
{
    protected $signature = 'entries:archive
                            {period : Period in YYYY-MM}
                            {--tenant= : Archive only for a specific tenant_id}
                            {--account= : Archive only for a specific account id}
                            {--force : Re-archive even if already archived}';

    protected $description = 'Archive entries for a given month (YYYY-MM)';

    public function handle(): int
    {
        $period = (string) $this->argument('period');

        if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $period)) {
            $this->error('Invalid period format. Use YYYY-MM (01-12)');
            return Command::FAILURE;
        }

        $this->info("Archiving entries for period: {$period}");

        $query = Account::query();

        if ($tenantId = $this->option('tenant')) {
            $query->where('tenant_id', $tenantId);
            $this->info("Tenant filter: {$tenantId}");
        }

        if ($accountId = $this->option('account')) {
            $query->where('id', $accountId);
            $this->info("Account filter: {$accountId}");
        }

        $force = (bool) $this->option('force');

        $total = (clone $query)->count();
        if ($total === 0) {
            $this->warn('No accounts found for the given filters.');
            return Command::SUCCESS;
        }

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $archived = 0;
        $skipped  = 0;
        $failed   = 0;

        $query->orderBy('id')->chunkById(200, function ($accounts) use (
            $period,
            $force,
            &$archived,
            &$skipped,
            &$failed,
            $bar
        ) {
            foreach ($accounts as $account) {
                try {
                    $result = AccountMonthService::archivePeriod(
                        (int) $account->tenant_id,
                        (int) $account->id,
                        $period,
                        $force
                    );

                    if ($result) {
                        $archived++;
                    } else {
                        $skipped++;
                    }

                } catch (Throwable $e) {
                    $failed++;
                    $this->newLine();
                    $this->error("❌ Account {$account->id} failed: {$e->getMessage()}");
                    $bar->display();
                } finally {
                    $bar->advance();
                }
            }
        });

        $bar->finish();
        $this->newLine();

        $this->info("Summary for {$period}:");
        $this->line("  ✅ Archived: {$archived}");
        $this->line("  ⏭️ Skipped (already archived): {$skipped}");
        $this->line("  ❌ Failed: {$failed}");

        return $failed ? Command::FAILURE : Command::SUCCESS;
    }
}
