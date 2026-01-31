<div class="container py-4">
    <h4>Create Tenant</h4>

    <div class="row g-3">
        <div class="col-md-6">
            <input class="form-control" placeholder="Owner name"
                wire:model.defer="owner_name">
            @error('owner_name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <input class="form-control" placeholder="Owner email"
                wire:model.defer="owner_email">
            @error('owner_email')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <input type="password" class="form-control"
                placeholder="Password"
                wire:model.defer="password">
            @error('password')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <input class="form-control" placeholder="Business name"
                wire:model.defer="business_name">
            @error('business_name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <select class="form-select"
                wire:model.defer="subscription_period">
                <option value="monthly">Monthly</option>
                <option value="yearly">Yearly</option>
            </select>
            @error('subscription_period')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <input type="date" class="form-control"
                wire:model.defer="subscription_ends_at">
            @error('subscription_ends_at')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <button wire:click="save" class="btn btn-dark">
                Create Tenant
            </button>
        </div>
        @error('create')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
