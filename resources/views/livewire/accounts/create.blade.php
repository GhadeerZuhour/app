<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">{{ __('accounts.create_title') }}</h4>
            <div class="text-muted small">{{ __('accounts.create_subtitle') }}</div>
        </div>

        <a href="{{ route('accounts.index') }}" class="btn btn-outline-secondary">
            ← {{ __('general.cancel') }}
        </a>
    </div>

    @if(session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">{{ __('accounts.name') }}</label>
                    <input type="text" class="form-control" wire:model.live="name" placeholder="{{ __('accounts.name_placeholder') }}">
                    @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">{{ __('accounts.type') }}</label>
                    <select class="form-select" wire:model.live="type">
                        <option value="bank">{{ __('accounts.type_bank') }}</option>
                        <option value="cash">{{ __('accounts.type_cash') }}</option>
                    </select>
                    @error('type') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">{{ __('accounts.currency') }}</label>
                    <select class="form-select" wire:model.live="currency_id">
                        <option value="">{{ __('accounts.select_currency') }}</option>
                        @foreach($currencies as $c)
                            <option value="{{ $c['id'] }}">{{ $c['code'] }} - {{ $c['name'] }}</option>
                        @endforeach
                    </select>
                    @error('currency_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                {{-- bank only --}}
                @if($type === 'bank')
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('accounts.bank') }}</label>
                        <select class="form-select" wire:model.live="bank_id">
                            <option value="">{{ __('accounts.select_bank') }}</option>
                            @foreach($banks as $b)
                                <option value="{{ $b['id'] }}">
                                    {{ $b['name'] }} @if(!empty($b['branch'])) - {{ $b['branch'] }} @endif
                                </option>
                            @endforeach
                        </select>
                        @error('bank_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                @endif

                <div class="col-md-3">
                    <label class="form-label fw-semibold">{{ __('accounts.opening_balance') }}</label>
                    <input type="number" step="0.01" class="form-control" wire:model.live="balance">
                    @error('balance') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" wire:model.live="is_active" id="active">
                        <label class="form-check-label fw-semibold" for="active">
                            {{ __('accounts.active') }}
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('accounts.index') }}" class="btn btn-outline-secondary">
                    {{ __('general.cancel') }}
                </a>
                <button class="btn btn-primary px-4" wire:click="save">
                    {{ __('general.save') }}
                </button>
            </div>

        </div>
    </div>
</div>
