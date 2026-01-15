<div class="container-fluid py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">{{ __('accounts.title') }}</h4>
            <div class="text-muted small">{{ __('accounts.subtitle') }}</div>
        </div>

        <a href="{{ route('accounts.create') }}" class="btn btn-primary">
            + {{ __('accounts.create') }}
        </a>
    </div>

    {{-- Filters --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text"
                           wire:model.live="search"
                           class="form-control"
                           placeholder="{{ __('accounts.search') }}">
                </div>

                <div class="col-md-3">
                    <select wire:model.live="type" class="form-select">
                        <option value="">{{ __('accounts.all_types') }}</option>
                        <option value="bank">{{ __('accounts.bank') }}</option>
                        <option value="cash">{{ __('accounts.cash') }}</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select wire:model.live="status" class="form-select">
                        <option value="">{{ __('accounts.all_status') }}</option>
                        <option value="active">{{ __('accounts.active') }}</option>
                        <option value="inactive">{{ __('accounts.inactive') }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('accounts.name') }}</th>
                        <th>{{ __('accounts.type') }}</th>
                        <th>{{ __('accounts.bank') }}</th>
                        <th>{{ __('accounts.currency') }}</th>
                        <th class="text-end">{{ __('accounts.balance') }}</th>
                        <th class="text-center">{{ __('accounts.status') }}</th>
                        <th class="text-center">{{ __('general.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($accounts as $account)
                        <tr>
                            <td class="fw-semibold">{{ $account->name }}</td>

                            <td>
                                <span class="badge bg-secondary">
                                    {{ $account->type }}
                                </span>
                            </td>

                            <td>
                                {{ $account->bank?->name ?? '—' }}
                            </td>

                            <td>
                                {{ $account->currency?->code }}
                            </td>

                            <td class="text-end">
                                {{ number_format($account->balance, 2) }}
                            </td>

                            <td class="text-center">
                                <button class="btn btn-sm {{ $account->is_active ? 'btn-success' : 'btn-outline-secondary' }}"
                                        wire:click="toggleStatus({{ $account->id }})">
                                    {{ $account->is_active ? __('accounts.active') : __('accounts.inactive') }}
                                </button>
                            </td>

                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary" disabled>
                                    {{ __('general.edit') }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                {{ __('accounts.no_accounts') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $accounts->links() }}
            </div>
        </div>
    </div>
</div>
