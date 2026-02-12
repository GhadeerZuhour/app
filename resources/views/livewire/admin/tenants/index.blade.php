<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Tenants</h4>

        <a href="{{ route('admin.tenants.create') }}" class="btn btn-dark">
            + Create Tenant
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Owner Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Subscription</th>
                        <th>Ends At</th>
                        <th>Domain</th>

                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($tenants as $tenant)

                    <tr>
                        {{-- Tenant Name --}}
                        <td>
                            {{ $tenant->meta?->name ?? '—' }}
                        </td>

                        {{-- Owner Name --}}
                        <td>
                            {{ $tenant->meta?->owner?->name ?? '—' }}
                        </td>

                        {{-- Owner Email --}}
                        <td>
                            {{ $tenant->meta?->owner_email ?? '—' }}
                        </td>

                        {{-- Active Status --}}
                        <td>
                            @if($tenant->meta?->is_active)
                            <span class="badge bg-success">ACTIVE</span>
                            @else
                            <span class="badge bg-danger">SUSPENDED</span>
                            @endif
                        </td>

                        {{-- Subscription Period --}}
                        <td>
                            {{ strtoupper($tenant->meta?->subscription_period ?? '-') }}
                        </td>

                        {{-- Ends At --}}
                        <td>
                           {{ $tenant->meta->computedEndsAt()}}

                        </td>


                        {{-- Domain --}}
                        <td>
                            {{ optional($tenant->domains->first())->domain ?? '—' }}
                        </td>



                        {{-- Actions --}}
                        <td class="text-end">
                            <a href="{{ route('admin.tenants.edit', $tenant->id) }}"
                                class="btn btn-sm btn-outline-primary">
                                Edit
                            </a>

                            <a class="btn btn-sm btn-outline-secondary"
                                href="{{ route('admin.tenants.receipt', $tenant->id) }}" target="_blank">
                                Receipt PDF
                            </a>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            No tenants found.
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $tenants->links() }}
    </div>

</div>