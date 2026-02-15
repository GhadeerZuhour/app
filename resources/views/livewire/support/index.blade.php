<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Support</h4>
        <a class="btn btn-dark" href="{{ route('tenant.support.create') }}">+ New Ticket</a>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-md-3">
            <select class="form-select" wire:model="status">
                <option value="">All Status</option>
                <option value="new">NEW</option>
                <option value="in_progress">IN PROGRESS</option>
                <option value="resolved">RESOLVED</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" wire:model="type">
                <option value="">All Types</option>
                <option value="general">General</option>
                <option value="renewal">Renewal</option>
                <option value="bug">Bug</option>
                <option value="invoice">Invoice</option>
            </select>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Type</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($tickets as $t)
                    <tr>
                        <td>{{ $t->subject }}</td>
                        <td><span class="badge bg-secondary">{{ strtoupper($t->status) }}</span></td>
                        <td>{{ strtoupper($t->type) }}</td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('tenant.support.show', $t->id) }}">Open</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">No tickets</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $tickets->links() }}</div>
</div>
