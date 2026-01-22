<div class="container py-4">
    <h4>Create Business</h4>

    <div class="row g-3">
        <div class="col-md-6">
            <input class="form-control" placeholder="Owner name"
                wire:model.defer="owner_name">
        </div>

        <div class="col-md-6">
            <input class="form-control" placeholder="Owner email"
                wire:model.defer="owner_email">
        </div>

        <div class="col-md-6">
            <input type="password" class="form-control"
                placeholder="Password"
                wire:model.defer="password">
        </div>

        <div class="col-md-6">
            <input class="form-control" placeholder="Business name"
                wire:model.defer="business_name">
        </div>

        <div class="col-md-6">
            <select class="form-select"
                wire:model.defer="subscription_period">
                <option value="monthly">Monthly</option>
                <option value="yearly">Yearly</option>
            </select>
        </div>

        <div class="col-md-6">
            <input type="date" class="form-control"
                wire:model.defer="subscription_ends_at">
        </div>

        <div class="col-12">
            <button wire:click="save" class="btn btn-dark">
                Create Business
            </button>
        </div>
        @error('create')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
</div>