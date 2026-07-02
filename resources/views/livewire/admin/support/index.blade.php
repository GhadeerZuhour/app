<div class="container-fluid py-3">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-0">Support Dashboard</h4>
            <div class="text-muted small">Central tickets (all tenants)</div>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('admin.dashboard') }}">
            Back
        </a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card p-3">
                <div class="text-muted small">New</div>
                <div class="fs-3 fw-semibold">{{ $counts['new'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <div class="text-muted small">In Progress</div>
                <div class="fs-3 fw-semibold">{{ $counts['in_progress'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <div class="text-muted small">Resolved</div>
                <div class="fs-3 fw-semibold">{{ $counts['resolved'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <div class="text-muted small">Renewal Requests</div>
                <div class="fs-3 fw-semibold">{{ $counts['renewal'] ?? 0 }}</div>
            </div>
        </div>
    </div>

    <div class="card p-3 mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">Search</label>
                <input class="form-control" placeholder="subject / requester" wire:model.live="q">
            </div>

            <div class="col-md-3">
                <label class="form-label small">Status</label>
                <select class="form-select" wire:model.live="status">
                    <option value="">All</option>
                    <option value="new">New</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label small">Type</label>
                <select class="form-select" wire:model.live="type">
                    <option value="">All</option>
                    <option value="general">General</option>
                    <option value="renewal">Renewal</option>
                    <option value="bug">Bug</option>
                    <option value="invoice">Invoice</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label small">Priority</label>
                <select class="form-select" wire:model.live="priority">
                    <option value="">All</option>
                    <option value="low">Low</option>
                    <option value="normal">Normal</option>
                    <option value="high">High</option>
                </select>
            </div>

            <div class="col-12 d-flex gap-2 mt-2">
                <button class="btn btn-outline-dark" wire:click="clearFilters">Clear</button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                <tr>
                    <th>Subject</th>
                    <th>Requester</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($rows as $t)
                    <tr>
                        <td class="fw-semibold">{{ $t->subject }}</td>
                        <td>{{ $t->requester_name }}</td>
                        <td>{{ $t->requester_email }}</td>
                        <td>{{ strtoupper($t->type) }}</td>
                        <td>
                            @php
                                $badge = match($t->status) {
                                    'new' => 'bg-primary',
                                    'in_progress' => 'bg-warning text-dark',
                                    'resolved' => 'bg-success',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $badge }}">{{ strtoupper($t->status) }}</span>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary"
                               href="{{ route('admin.support.show', $t->id) }}">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-5">No tickets</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3">
            {{ $rows->links() }}
        </div>
    </div>
</div>
