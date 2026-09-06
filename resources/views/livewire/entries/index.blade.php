<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">{{ __('general.entries') }}</h4>
            <div class="text-muted small">Track incoming and outgoing cash activity</div>
        </div>

        <a href="{{ route('entries.create') }}" class="btn btn-primary">
            {{ __('general.create_entry') }}
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex gap-2 flex-wrap">
                <input type="month" wire:model.live="month" class="form-control" style="max-width:200px">

                <select wire:model.live="type" class="form-select" style="max-width:200px">
                    <option value="">{{ __('entries.all_types') ?? 'All Types' }}</option>
                    <option value="cash">{{ __('payments.cash') }}</option>
                    <option value="bank">{{ __('payments.bank') }}</option>
                    <option value="check">{{ __('payments.check') }}</option>
                </select>

                <input wire:model.live="search" class="form-control" style="max-width:260px"
                       placeholder="{{ __('entries.search') ?? 'Search reference or description...' }}">

                <div class="form-check ms-auto align-self-center">
                    <input class="form-check-input" type="checkbox" wire:model.live="showArchived" id="arch">
                    <label class="form-check-label" for="arch">
                        {{ __('entries.show_archived') ?? 'Show Archived' }}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('general.date') }}</th>
                        <th>Reference</th>
                        <th>Description</th>
                        <th>{{ __('general.account') }}</th>
                        <th>{{ __('general.payment_method') }}</th>
                        <th>Flow</th>
                        <th class="text-end">{{ __('entries.total_amount') ?? 'Total' }}</th>
                        <th class="text-center">{{ __('entries.status') ?? 'Status' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $entry)
                        <tr>
                            <td>{{ $entry->entry_date }}</td>
                            <td class="text-muted">{{ $entry->reference_no ?: '—' }}</td>
                            <td>{{ $entry->description ?: '—' }}</td>
                            <td class="fw-semibold">{{ $entry->account?->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-secondary text-uppercase">{{ $entry->payment_method }}</span>
                            </td>
                            <td>
                                @if($entry->direction === 'out')
                                    <span class="badge bg-danger-subtle text-danger">Outgoing</span>
                                @else
                                    <span class="badge bg-success-subtle text-success">Incoming</span>
                                @endif
                            </td>
                            <td class="text-end fw-semibold {{ $entry->direction === 'out' ? 'text-danger' : 'text-success' }}">
                                {{ $entry->direction === 'out' ? '-' : '+' }}₪{{ number_format($entry->total_amount, 2) }}
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $entry->is_archived ? 'bg-warning text-dark' : 'bg-success' }}">
                                    {{ $entry->is_archived ? __('entries.archived') ?? 'Archived' : __('entries.active') ?? 'Active' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                {{ __('entries.no_entries') ?? 'No entries found.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $entries->links() }}
            </div>
        </div>
    </div>
</div>
