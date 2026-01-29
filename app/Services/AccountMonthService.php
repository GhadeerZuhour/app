<?php

namespace App\Services;

use App\Models\Entry;
use App\Models\ZeroBalance;
use App\Models\AccountMonthClosing;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AccountMonthService
{
    public static function monthStartFromPeriod(string $period): Carbon
    {
        return Carbon::createFromFormat('Y-m', $period)->startOfMonth();
    }

    public static function getOpening(int $tenantId, int $accountId, string $period): float
    {
        $monthDate = self::monthStartFromPeriod($period)->toDateString();

        return (float) (ZeroBalance::where('tenant_id', $tenantId)
            ->where('account_id', $accountId)
            ->whereDate('month', $monthDate)
            ->value('zero_amount') ?? 0);
    }

    public static function sumMonth(int $tenantId, int $accountId, string $period): array
    {
        $start = self::monthStartFromPeriod($period);
        $end = (clone $start)->endOfMonth();

        $entries = Entry::query()
            ->where('tenant_id', $tenantId)
            ->where('account_id', $accountId)
            ->whereBetween('entry_date', [$start->toDateString(), $end->toDateString()])
            ->with(['checks']) // تأكدي علاقة checks موجودة
            ->get();

        $in = 0.0;
        $out = 0.0;

        foreach ($entries as $e) {
            // CHECK: sum from checks table
            if ($e->payment_method === 'check') {
                $in += (float) $e->checks->where('direction', 'in')->sum('amount');
                $out += (float) $e->checks->where('direction', 'out')->sum('amount');
                continue;
            }

            // CASH / BANK TRANSFER: sum from entries amount
            if ($e->direction === 'in') {
                $in += (float) $e->amount;
            } elseif ($e->direction === 'out') {
                $out += (float) $e->amount;
            }
        }

        return [$in, $out];
    }

    public static function ensureZeroForPeriod(int $tenantId, int $accountId, string $period): void
    {
        $monthDate = self::monthStartFromPeriod($period)->toDateString();

        $exists = ZeroBalance::where('tenant_id', $tenantId)
            ->where('account_id', $accountId)
            ->whereDate('month', $monthDate)
            ->exists();

        if ($exists) return;

        $prev = Carbon::createFromFormat('Y-m', $period)->subMonth()->format('Y-m');

        $prevClosing = AccountMonthClosing::where('tenant_id', $tenantId)
            ->where('account_id', $accountId)
            ->where('period', $prev)
            ->value('closing');

        if ($prevClosing !== null) {
            ZeroBalance::create([
                'tenant_id' => $tenantId,
                'account_id' => $accountId,
                'month' => $monthDate,
                'zero_amount' => (float) $prevClosing,
            ]);
        }
    }

   public static function archivePeriod(int $tenantId, int $accountId, string $period, bool $force = false): bool
{
    $start = self::monthStartFromPeriod($period);
    $end = (clone $start)->endOfMonth();

    // already archived?
    $already = AccountMonthClosing::where('tenant_id', $tenantId)
        ->where('account_id', $accountId)
        ->where('period', $period)
        ->exists();

    if ($already && !$force) {
        return false; // ⏭️ skipped
    }

    \Illuminate\Support\Facades\DB::transaction(function () use ($tenantId, $accountId, $period, $start, $end) {
        $opening = self::getOpening($tenantId, $accountId, $period);
        [$in, $out] = self::sumMonth($tenantId, $accountId, $period);
        $closing = $opening + $in - $out;

        AccountMonthClosing::updateOrCreate(
            ['tenant_id' => $tenantId, 'account_id' => $accountId, 'period' => $period],
            [
                'opening' => $opening,
                'total_in' => $in,
                'total_out' => $out,
                'closing' => $closing,
                'archived_at' => now(),
            ]
        );

        Entry::where('tenant_id', $tenantId)
            ->where('account_id', $accountId)
            ->whereBetween('entry_date', [$start->toDateString(), $end->toDateString()])
            ->update([
                'is_archived' => true,
                'archived_at' => now(),
            ]);

        $next = Carbon::createFromFormat('Y-m', $period)->addMonth()->format('Y-m');
        self::ensureZeroForPeriod($tenantId, $accountId, $next);
    });

    return true; // ✅ archived
}

   
}
