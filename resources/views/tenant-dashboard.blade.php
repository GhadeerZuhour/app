<x-layouts.app :title="__('Tazreem Dashboard')">
    @php
        $tenantId = tenant_id();
        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->endOfMonth()->toDateString();

        $accounts = \App\Models\Account::where('tenant_id', $tenantId)
            ->with('currency')
            ->orderBy('name')
            ->get();

        $monthEntries = \App\Models\Entry::where('tenant_id', $tenantId)
            ->whereBetween('entry_date', [$monthStart, $monthEnd]);

        $incoming = (clone $monthEntries)->where('direction', 'in')->sum('total_amount');
        $outgoing = (clone $monthEntries)->where('direction', 'out')->sum('total_amount');
        $netCashFlow = $incoming - $outgoing;
        $entriesCount = (clone $monthEntries)->count();
        $accountsTotal = $accounts->sum(fn ($account) => (float) ($account->balance ?? 0));

        $upcomingCheckRows = \App\Models\CheckDetails::query()
            ->with('entry.account')
            ->whereHas('entry', fn ($query) => $query->where('tenant_id', $tenantId))
            ->whereDate('check_date', '>=', now()->toDateString())
            ->orderBy('check_date')
            ->limit(5)
            ->get();

        $upcomingChecksTotal = $upcomingCheckRows->sum('amount');

        $recentEntries = \App\Models\Entry::with('account')
            ->where('tenant_id', $tenantId)
            ->latest('entry_date')
            ->limit(7)
            ->get();

        $cashFlowMonths = collect(range(5, 0))->map(function ($monthsAgo) use ($tenantId) {
            $date = now()->subMonths($monthsAgo);
            $start = $date->copy()->startOfMonth()->toDateString();
            $end = $date->copy()->endOfMonth()->toDateString();

            $query = \App\Models\Entry::where('tenant_id', $tenantId)
                ->whereBetween('entry_date', [$start, $end]);

            $in = (clone $query)->where('direction', 'in')->sum('total_amount');
            $out = (clone $query)->where('direction', 'out')->sum('total_amount');

            return [
                'label' => $date->format('M'),
                'in' => (float) $in,
                'out' => (float) $out,
                'net' => (float) $in - (float) $out,
            ];
        });

        $chartMax = max(1, $cashFlowMonths->max(fn ($month) => max($month['in'], $month['out'])));
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-medium text-zinc-500">Cash-flow workspace</p>
                <h1 class="text-2xl font-semibold tracking-tight">Tazreem Dashboard</h1>
                <p class="mt-1 text-sm text-zinc-500">Know what came in, what went out, and what is coming next.</p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('accounts.index') }}"
                   class="inline-flex items-center justify-center rounded-lg border border-zinc-200 px-4 py-2 text-sm font-medium hover:bg-zinc-50 dark:border-zinc-700 dark:hover:bg-zinc-800">
                    Accounts
                </a>
                <a href="{{ route('entries.create') }}"
                   class="inline-flex items-center justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-zinc-800 dark:bg-white dark:text-zinc-900">
                    + Add transaction
                </a>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-zinc-500">Money in</p>
                    <span class="rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">This month</span>
                </div>
                <p class="mt-2 text-3xl font-semibold">₪{{ number_format((float) $incoming, 2) }}</p>
                <p class="mt-3 text-sm text-zinc-500">{{ number_format($entriesCount) }} recorded transactions</p>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-zinc-500">Money out</p>
                    <span class="rounded-full bg-rose-50 px-2 py-1 text-xs font-medium text-rose-700 dark:bg-rose-950 dark:text-rose-300">This month</span>
                </div>
                <p class="mt-2 text-3xl font-semibold">₪{{ number_format((float) $outgoing, 2) }}</p>
                <p class="mt-3 text-sm text-zinc-500">Operating cash outflow</p>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500">Net cash flow</p>
                <p class="mt-2 text-3xl font-semibold {{ $netCashFlow >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ $netCashFlow >= 0 ? '+' : '-' }}₪{{ number_format(abs((float) $netCashFlow), 2) }}
                </p>
                <p class="mt-3 text-sm text-zinc-500">Money in minus money out</p>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500">Available across accounts</p>
                <p class="mt-2 text-3xl font-semibold">₪{{ number_format((float) $accountsTotal, 2) }}</p>
                <p class="mt-3 text-sm text-zinc-500">{{ $accounts->count() }} cash & bank accounts</p>
            </div>
        </div>

        <div class="grid gap-4 xl:grid-cols-3">
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 xl:col-span-2">
                <div class="mb-5 flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h2 class="font-semibold">6-month cash flow</h2>
                        <p class="text-sm text-zinc-500">Incoming versus outgoing cash by month</p>
                    </div>
                    <div class="flex gap-3 text-xs text-zinc-500">
                        <span>■ Money in</span>
                        <span>□ Money out</span>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach ($cashFlowMonths as $month)
                        <div class="grid grid-cols-[42px_1fr_100px] items-center gap-3">
                            <span class="text-sm font-medium text-zinc-500">{{ $month['label'] }}</span>
                            <div class="space-y-1.5">
                                <div class="h-2.5 overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800">
                                    <div class="h-full rounded-full bg-zinc-900 dark:bg-zinc-100" style="width: {{ ($month['in'] / $chartMax) * 100 }}%"></div>
                                </div>
                                <div class="h-2.5 overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800">
                                    <div class="h-full rounded-full bg-zinc-400 dark:bg-zinc-500" style="width: {{ ($month['out'] / $chartMax) * 100 }}%"></div>
                                </div>
                            </div>
                            <span class="text-right text-xs font-medium {{ $month['net'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $month['net'] >= 0 ? '+' : '-' }}₪{{ number_format(abs($month['net']), 0) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h2 class="font-semibold">Upcoming checks</h2>
                        <p class="text-sm text-zinc-500">Future cash already visible</p>
                    </div>
                    <span class="text-sm font-semibold">₪{{ number_format((float) $upcomingChecksTotal, 0) }}</span>
                </div>

                <div class="mt-5 space-y-3">
                    @forelse ($upcomingCheckRows as $check)
                        <div class="rounded-lg border border-zinc-100 p-3 dark:border-zinc-800">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-sm font-medium">{{ $check->check_number }}</span>
                                <span class="text-sm font-semibold">₪{{ number_format((float) $check->amount, 0) }}</span>
                            </div>
                            <div class="mt-1 flex items-center justify-between gap-3 text-xs text-zinc-500">
                                <span>{{ $check->customer ?: 'Customer' }}</span>
                                <span>{{ \Illuminate\Support\Carbon::parse($check->check_date)->format('d M Y') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-lg border border-dashed border-zinc-200 p-6 text-center text-sm text-zinc-500 dark:border-zinc-700">
                            No upcoming checks.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 lg:col-span-2">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h2 class="font-semibold">Recent activity</h2>
                        <p class="text-sm text-zinc-500">Latest financial movements</p>
                    </div>
                    <a href="{{ route('entries.index') }}" class="text-sm font-medium underline underline-offset-4">All transactions</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-zinc-200 text-zinc-500 dark:border-zinc-800">
                            <tr>
                                <th class="py-3 pr-4 font-medium">Date</th>
                                <th class="py-3 pr-4 font-medium">Account</th>
                                <th class="py-3 pr-4 font-medium">Method</th>
                                <th class="py-3 pr-4 font-medium">Flow</th>
                                <th class="py-3 text-right font-medium">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @forelse ($recentEntries as $entry)
                                <tr>
                                    <td class="py-3 pr-4">{{ \Illuminate\Support\Carbon::parse($entry->entry_date)->format('d M Y') }}</td>
                                    <td class="py-3 pr-4">{{ $entry->account?->name ?? '—' }}</td>
                                    <td class="py-3 pr-4 capitalize">{{ $entry->payment_method }}</td>
                                    <td class="py-3 pr-4">
                                        <span class="rounded-full px-2 py-1 text-xs font-medium {{ $entry->direction === 'out' ? 'bg-rose-50 text-rose-700 dark:bg-rose-950 dark:text-rose-300' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' }}">
                                            {{ $entry->direction === 'out' ? 'Outgoing' : 'Incoming' }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right font-medium {{ $entry->direction === 'out' ? 'text-rose-600' : 'text-emerald-600' }}">
                                        {{ $entry->direction === 'out' ? '-' : '+' }}₪{{ number_format((float) $entry->total_amount, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-zinc-500">
                                        No activity yet. Run the demo seeder or add your first transaction.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <h2 class="font-semibold">Account snapshot</h2>
                <p class="mt-1 text-sm text-zinc-500">Where the business cash is held</p>

                <div class="mt-5 space-y-3">
                    @forelse ($accounts as $account)
                        <div class="flex items-center justify-between gap-4 rounded-lg border border-zinc-100 p-3 dark:border-zinc-800">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium">{{ $account->name }}</p>
                                <p class="text-xs uppercase text-zinc-500">{{ $account->type }} · {{ $account->currency?->code ?? 'ILS' }}</p>
                            </div>
                            <span class="text-sm font-semibold">₪{{ number_format((float) ($account->balance ?? 0), 0) }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-500">No accounts yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
