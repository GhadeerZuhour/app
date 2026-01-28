<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div class="container py-4">
    <div class="d-flex justify-content-between mb-3">
        <h4>Businesses</h4>
        <a href="{{ route('admin.businesses.create') }}" class="btn btn-dark">
            + New Business
        </a>
    </div>

    <input type="text" class="form-control mb-3"
           placeholder="Search business or owner..."
           wire:model.live="search">

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Business</th>
            <th>Owner</th>
            <th>Status</th>
            <th>Subscription</th>
            <th>Action</th>
        </tr>
        </thead>

        <tbody>
        @foreach($businesses as $tenant)
            <tr>
                <td>{{ $tenant->business_name }}</td>

                <td>
                    {{ $tenant->owner->name }}<br>
                    <small>{{ $tenant->owner->email }}</small>
                </td>

                <td>
                    <span class="badge {{ $tenant->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $tenant->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>

                <td>
                    {{ $tenant->subscription_ends_at?->format('Y-m-d') ?? 'Lifetime' }}
                </td>

                <td>
                    <button wire:click="toggleActive('{{ $tenant->id }}')"
                            class="btn btn-sm btn-outline-primary">
                        Toggle
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $businesses->links() }}
</div>
