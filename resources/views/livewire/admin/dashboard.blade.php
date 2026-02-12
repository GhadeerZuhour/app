<div class="container py-4">

    {{-- ✅ Cards --}}
    <div class="row mb-4">
        <x-dashboard-card title="Total Tenants" value="{{ $stats['total'] }}" color="secondary" />
        <x-dashboard-card title="Active" value="{{ $stats['active'] }}" color="success" />
        <x-dashboard-card title="Expired" value="{{ $stats['expired'] }}" color="danger" />
        <x-dashboard-card title="Expiring Soon" value="{{ $stats['expiringSoon'] }}" color="warning" />
    </div>

    {{-- ✅ Expiring soon --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Expiring Soon (7 days)</strong>
        </div>
        <div class="card-body">
            @forelse($expiring as $meta)
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span>{{ $meta->name }}</span>
                    <span class="badge bg-warning text-dark">
                        {{ $meta->daysLeft() }} days left
                    </span>
                </div>
            @empty
                <p class="mb-0 text-muted">No subscriptions expiring soon.</p>
            @endforelse
        </div>
    </div>

    {{-- ✅ Latest tenants --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Latest Tenants</strong>
        </div>
        <div class="card-body">
            @forelse($latestTenants as $tenant)
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span>{{ $tenant->meta?->name ?? '—' }}</span>
                    <span class="badge bg-secondary">{{ $tenant->meta?->statusLabel() ?? '—' }}</span>
                </div>
            @empty
                <p class="mb-0 text-muted">No tenants yet.</p>
            @endforelse
        </div>
    </div>

</div>
