<x-layouts.app :title="__('Tazreem Dashboard')">
    @php
        $tenantId = tenant_id();
        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->endOfMonth()->toDateString();

        $accountsCount = \App\Models\Account::where('tenant_id', $tenantId)->count();
        $monthEntries = \App\Models\Entry::where('tenant_id', $tenantId)
            ->whereBetween('entry_date', [$monthStart, $monthEnd]);
        $monthTotal = (clone $monthEntries)->sum('total_amount');
        $entriesCount = (clone $monthEntries)->count();

        $upcomingChecks = \App\Models\CheckDetails::query()
            ->whereHas('entry', fn ($query) => $query->where('tenant_id', $tenantId))
            ->whereDate('check_date', '>=', now()->toDateString())
            ->count();

        $recentEntries = \App\Models\Entry::with('account')
            ->where('tenant_id', $tenantId)
            ->latest('entry_date')
            ->limit(6)
            ->get();
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-medium text-zinc-500">Cash-flow workspace</p>
                <h1 class="text-2xl font-semibold tracking-tight">Tazreem Dashboard</h1>
                <p class="mt-1 text-sm text-zinc-500">A quick view of this month's financial activity.</p>
            </div>

            <a href="{{ route('entries.create') }}"
               class="inline-flex items-center justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-zinc-800 dark:bg-white dark:text-zinc-900">
                + Add transaction
            </a>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500">Accounts</p>
                <p class="mt-2 text-3xl font-semibold">{{ number_format($accountsCount) }}</p>
                <a href="{{ route('accounts.index') }}" class="mt-3 inline-block text-sm font-medium underline underline-offset-4">View accounts</a>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500">Transactions this month</p>
                <p class="mt-2 text-3xl font-semibold">{{ number_format($entriesCount) }}</p>
                <p class="mt-3 text-sm text-zinc-500">Cash, bank and checks</p>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500">Recorded value this month</p>
                <p class="mt-2 text-3xl font-semibold">{{ number_format((float) $monthTotal, 2) }}</p>
                <p class="mt-3 text-sm text-zinc-500">Across all payment methods</p>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500">Upcoming checks</p>
                <p class="mt-2 text-3xl font-semibold">{{ number_format($upcomingChecks) }}</p>
                <p class="mt-3 text-sm text-zinc-500">Scheduled from today onward</p>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 lg:col-span-2">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h2 class="font-semibold">Recent activity</h2>
                        <p class="text-sm text-zinc-500">Latest recorded transactions</p>
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
                                <th class="py-3 text-right font-medium">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @forelse ($recentEntries as $entry)
                                <tr>
                                    <td class="py-3 pr-4">{{ \Illuminate\Support\Carbon::parse($entry->entry_date)->format('d M Y') }}</td>
                                    <td class="py-3 pr-4">{{ $entry->account?->name ?? '—' }}</td>
                                    <td class="py-3 pr-4 capitalize">{{ $entry->payment_method }}</td>
                                    <td class="py-3 text-right font-medium">{{ number_format((float) $entry->total_amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-zinc-500">
                                        No activity yet. Run the demo seeder or add your first transaction.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <h2 class="font-semibold">Demo flow</h2>
                <p class="mt-1 text-sm text-zinc-500">A simple story to show customers or investors.</p>

                <ol class="mt-5 space-y-4 text-sm">
                    <li><span class="font-semibold">1.</span> Review cash and bank accounts.</li>
                    <li><span class="font-semibold">2.</span> Add a cash or bank transaction.</li>
                    <li><span class="font-semibold">3.</span> Add multiple scheduled checks.</li>
                    <li><span class="font-semibold">4.</span> Return here to show the updated overview.</li>
                </ol>

                <div class="mt-6 grid gap-2">
                    <a href="{{ route('accounts.index') }}" class="rounded-lg border border-zinc-200 px-3 py-2 text-center text-sm font-medium hover:bg-zinc-50 dark:border-zinc-700 dark:hover:bg-zinc-800">Accounts</a>
                    <a href="{{ route('entries.index') }}" class="rounded-lg border border-zinc-200 px-3 py-2 text-center text-sm font-medium hover:bg-zinc-50 dark:border-zinc-700 dark:hover:bg-zinc-800">Transactions</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
