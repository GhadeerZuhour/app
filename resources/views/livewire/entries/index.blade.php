<div class="card">
    <div class="card-header">
        <h5 class="mb-3">Entries</h5>

        <div class="row g-2">
            <div class="col-md-3">
                <input type="text" wire:model.live="search" class="form-control"
                       placeholder="Search reference or description">
            </div>

            <div class="col-md-2">
                <select wire:model.live="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>

            <div class="col-md-2">
                <input type="date" wire:model.live="fromDate" class="form-control">
            </div>

            <div class="col-md-2">
                <input type="date" wire:model.live="toDate" class="form-control">
            </div>

            <div class="col-md-3 text-end">
                <a href="{{ route('entries.create') }}" class="btn btn-primary">
                    + New Entry
                </a>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Reference</th>
                    <th>Type</th>
                    <th class="text-end">Amount</th>
                    <th>Bank Transfer</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($entries as $entry)
                    <tr>
                        <td>{{ $entry->id }}</td>
                        <td>{{ $entry->entry_date }}</td>
                        <td>{{ $entry->reference_no }}</td>

                        <td>
                            <span class="badge bg-{{ $entry->type === 'income' ? 'success' : 'danger' }}">
                                {{ ucfirst($entry->type) }}
                            </span>
                        </td>

                        <td class="text-end">
                            {{ number_format($entry->amount, 2) }}
                        </td>

                        <td>
                            {{ $entry->bankTransfer?->reference ?? '-' }}
                        </td>

                        <td>
                           
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            No entries found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $entries->links() }}
    </div>
</div>
