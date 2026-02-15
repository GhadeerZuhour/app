<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Tenants</h4>
        <a class="btn btn-dark" href="{{ route('admin.tenants.create') }}">Create</a>
    </div>

    <input class="form-control mb-3" placeholder="Search name/email..." wire:model.live="q">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Owner</th>
                    <th>Status</th>
                    <th>Ends</th>
                    <th>Domain</th>
                    <th>DB</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($tenants as $t)
                    <tr>
                        <td>{{ $t->meta?->name ?? '—' }}</td>
                        <td>{{ $t->meta?->owner_email ?? '—' }}</td>
                        <td>
                            @if(($t->meta?->is_active ?? false) === true)
                                ACTIVE
                            @else
                                SUSPENDED
                            @endif
                        </td>
                        <td>{{ optional($t->meta?->subscription_ends_at)?->format('Y-m-d') ?? '—' }}</td>
                        <td>{{ optional($t->domains->first())->domain ?? '—' }}</td>
                        <td>{{ $t->tenancy_db_name ?? '—' }}</td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary"
                               href="{{ route('admin.tenants.edit', $t->id) }}">Edit</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $tenants->links() }}
    </div>
</div>
