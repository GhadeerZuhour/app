<div class="card p-4">
    <h4 class="mb-3">{{ $tenant->data['tenant_name'] ?? 'Tenant' }}</h4>

    <div class="mb-2"><strong>ID:</strong> {{ $tenant->id }}</div>
    <div class="mb-2"><strong>Domain:</strong> {{ $domain ?? '—' }}</div>
    <div class="mb-2">
        <strong>Status:</strong>
        {{ ($tenant->data['subscription']['is_active'] ?? false) ? 'Active' : 'Suspended' }}
    </div>

    <hr>

    <pre class="bg-light p-3 rounded">{{ json_encode($tenant->data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
</div>
