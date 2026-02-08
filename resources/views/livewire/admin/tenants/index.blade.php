<div>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Tenants</h4>

        <div class="d-flex gap-2">
            <input type="text" class="form-control" style="width: 260px" placeholder="Search tenant name..."
                wire:model.live="search">
            <a class="btn btn-primary" href="{{ route('admin.tenants.create') }}">Add Tenant</a>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Domain</th>
                        <th>Status</th>
                        <th>Ends At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($tenants as $tenant)
                        @php
                            $name = $tenant->data['tenant_name'] ?? '—';
                            $isActive = $tenant->data['subscription']['is_active'] ?? false;
                            $endsAt = $tenant->subscription_ends_at?->format('Y-m-d') ?? 'Lifetime';
                            $domain = $tenant->domains()->first()?->domain;
                            $sub = $tenant->subscriptionPeriod;
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $tenant->name }}</div>
                                <div class="text-muted text-xs">{{ $tenant->id }}</div>
                            </td>

                            <td>{{ $tenant->domain ?? '—' }}</td>

                            <td>
                                @php
                                    $status = $tenant->subscription_status;
                                    $class = $status === 'ACTIVE' ? 'success' : ($status === 'EXPIRED' ? 'danger' : 'secondary');
                                @endphp

                                <span class="badge bg-{{ $class }}">
                                    {{ $status }}
                                </span>
                            </td>

                            <td>
                                {{ $tenant->ends_at_label  }} {{ $tenant->subscription_period }}


                            </td>


                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">

                                    {{-- View tenant --}}
                                    <a href="{{ route('admin.tenants.show', $tenant->id) }}"
                                        class="btn btn-icon-only btn-outline-primary d-flex align-items-center justify-content-center"
                                        style="width:38px;height:38px" data-bs-toggle="tooltip" title="View Tenant">
                                        <i class="ni ni-zoom-split-in fs-5"></i>
                                    </a>



                                    {{-- Activate / Suspend --}}
                                    @if(data_get($tenant->data, 'subscription.is_active'))
                                        <button wire:click="toggle('{{ $tenant->id }}')"
                                            class="btn btn-icon-only btn-outline-success d-flex align-items-center justify-content-center" data-bs-toggle="tooltip"
                                            title="Suspend Tenant">
                                            <i class="ni ni-button-power fs-5"></i>
                                        </button>
                                    @else
                                        <button wire:click="toggle('{{ $tenant->id }}')"
                                            class="btn btn-icon-only btn-outline-secondary d-flex align-items-center justify-content-center" data-bs-toggle="tooltip"
                                            title="Activate Tenant">
                                            <i class="ni ni-check-bold fs-5"></i>
                                        </button>
                                    @endif


                                    {{-- Delete --}}
                                    <button wire:click="delete('{{ $tenant->id }}')"
                                        onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                        class="btn btn-icon-only btn-outline-danger d-flex align-items-center justify-content-center" data-bs-toggle="tooltip"
                                        title="Delete Tenant">
                                        <i class="ni ni-fat-remove fs-5"></i>
                                    </button>

                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-3">
            {{ $tenants->links() }}
        </div>
    </div>
</div>