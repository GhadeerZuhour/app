<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Tenant Details</h4>
        <a class="btn btn-outline-secondary" href="{{ route('admin.tenants.index') }}">Back</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="text-muted text-xs">Tenant Name</div>
                    <div class="fw-semibold">{{ $tenant->tenant_name ?? '—' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted text-xs">Tenant ID</div>
                    <div class="fw-semibold">{{ $tenant->id }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted text-xs">Domain</div>
                    <div class="fw-semibold">{{ $domain ?? '—' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted text-xs">Status</div>
                    <span class="badge bg-{{ $tenant->is_active ? 'success' : 'secondary' }}">
                        {{ $tenant->is_active ? 'Active' : 'Suspended' }}
                    </span>
                </div>

                <div class="col-md-6">
                    <div class="text-muted text-xs">Subscription Period</div>
                    <div class="fw-semibold text-capitalize">
                        {{ $tenant->subscription_period ?? '—' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted text-xs">Ends At</div>
                    <div class="fw-semibold">
                        {{ $tenant->subscription_ends_at?->format('Y-m-d') ?? 'Lifetime' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($domain)
        <div class="mt-3">
            <a class="btn btn-outline-primary" target="_blank" href="http://{{ $domain }}:8000">
                Open Tenant
            </a>
        </div>
    @endif
</div>
