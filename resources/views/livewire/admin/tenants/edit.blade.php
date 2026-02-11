<div class="container py-4">
    <h4>Edit Tenant</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="update" class="row g-3">
        <div class="col-md-6">
            <input class="form-control" placeholder="Tenant name" wire:model.defer="tenant_name">
            @error('tenant_name') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <input class="form-control" placeholder="Tenant type" wire:model.defer="tenant_type">
        </div>

        <div class="col-md-4">
            <select class="form-select" wire:model.defer="subscription_period">
                <option value="monthly">Monthly</option>
                <option value="yearly">Yearly</option>
            </select>
        </div>

        <div class="col-md-4">
            <input type="date" class="form-control" wire:model.defer="subscription_ends_at">
        </div>

        <div class="col-md-4 d-flex align-items-center gap-2">
            <input type="checkbox" class="form-check-input" wire:model="is_active" id="is_active">
            <label class="form-check-label" for="is_active">Active</label>
        </div>

        <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-dark">Save</button>
            <button type="button" class="btn btn-outline-primary" wire:click="toggleActive">Toggle Active</button>
            <button type="button" class="btn btn-outline-success" wire:click="extend(30)">Extend 30 days</button>
        </div>
    </form>
</div>
