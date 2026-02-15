<div class="container py-4">
    <h4 class="mb-3">New Support Ticket</h4>

    <div class="card">
        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Type</label>
                    <select class="form-select" wire:model="type">
                        <option value="general">General</option>
                        <option value="renewal">Renewal Request</option>
                        <option value="bug">System Issue</option>
                        <option value="invoice">Invoice Request</option>
                    </select>
                    @error('type') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Priority</label>
                    <select class="form-select" wire:model="priority">
                        <option value="low">Low</option>
                        <option value="normal">Normal</option>
                        <option value="high">High</option>
                    </select>
                </div>

                @if($type === 'renewal')
                    <div class="col-md-2">
                        <label class="form-label">Months</label>
                        <select class="form-select" wire:model="months">
                            <option value="1">1</option>
                            <option value="3">3</option>
                            <option value="6">6</option>
                            <option value="12">12</option>
                        </select>
                        @error('months') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Payment</label>
                        <select class="form-select" wire:model="payment_method">
                            <option value="bank">Bank</option>
                            <option value="cash">Cash</option>
                            <option value="stripe">Stripe</option>
                        </select>
                        @error('payment_method') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                @endif

                <div class="col-12">
                    <label class="form-label">Subject</label>
                    <input class="form-control" wire:model.defer="subject">
                    @error('subject') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" rows="4" wire:model.defer="description"></textarea>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-dark" wire:click="save">Submit</button>
                    <a class="btn btn-outline-secondary" href="{{ route('tenant.support.index') }}">Back</a>
                </div>

            </div>
        </div>
    </div>
</div>
