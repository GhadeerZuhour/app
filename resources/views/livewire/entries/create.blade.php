<div class="container-fluid py-3">
    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h4 class="mb-0">{{ __('general.create_entry') }}</h4>
            <div class="text-muted small">
                {{ __('general.entries') }} • {{ __('payments.check') }}/{{ __('payments.cash') }}/{{ __('payments.bank') }}
            </div>
        </div>

        <a href="{{ route('entries.index') }}" class="btn btn-outline-secondary">
            ← {{ __('general.cancel') }}
        </a>
    </div>

    {{-- Alerts --}}
    @if(session()->has('success'))
        <div class="alert alert-success d-flex align-items-center gap-2">
            <span class="fw-semibold">✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($needsZeroBalance)
        <div class="alert alert-warning d-flex align-items-start gap-2">
            <span class="fw-semibold">!</span>
            <div>
                <div class="fw-semibold">{{ __('entries.zero_required') }}</div>
                <div class="small text-muted">{{ __('entries.zero_hint') ?? '' }}</div>
            </div>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">

            {{-- Step Indicator (only for check flow) --}}
            @if($payment_method === 'check')
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <span class="badge rounded-pill {{ !$generated ? 'text-bg-primary' : 'text-bg-light text-muted' }}">
                        1 • {{ __('entries.step_template') ?? 'Template' }}
                    </span>
                    <span class="text-muted">→</span>
                    <span class="badge rounded-pill {{ $generated ? 'text-bg-primary' : 'text-bg-light text-muted' }}">
                        2 • {{ __('entries.step_review') ?? 'Review & Update' }}
                    </span>

                    @if($generated)
                        <span class="ms-auto badge text-bg-success">
                            {{ count($rows) }} {{ __('entries.checks_count_label') ?? 'checks' }}
                        </span>
                    @endif
                </div>
            @endif

            {{-- Section: Entry Header --}}
            <div class="p-3 rounded-4 border bg-light-subtle mb-3">
                <div class="row g-3">
                    <div class="col-lg-5">
                        <label class="form-label fw-semibold">{{ __('general.account') }}</label>
                        <select wire:model.live="account_id" class="form-select">
                            <option value="">{{ __('entries.select_account') }}</option>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc['id'] }}">{{ $acc['name'] }}</option>
                            @endforeach
                        </select>
                        @error('account_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-lg-3">
                        <label class="form-label fw-semibold">{{ __('general.date') }}</label>
                        <input type="date" wire:model.live="entry_date" class="form-control">
                        @error('entry_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label fw-semibold">{{ __('general.payment_method') }}</label>
                        <select wire:model.live="payment_method" class="form-select" @disabled($needsZeroBalance)>
                            <option value="cash">{{ __('payments.cash') }}</option>
                            <option value="bank">{{ __('payments.bank') }}</option>
                            <option value="check">{{ __('payments.check') }}</option>
                        </select>
                        @error('payment_method') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            {{-- =========================
                 CHECK FLOW
            ========================== --}}
            @if($payment_method === 'check')

                {{-- STEP 1: Template --}}
                @if(!$generated)
                    <div class="row g-3">
                        <div class="col-lg-3">
                            <div class="p-3 rounded-4 border bg-white h-100">
                                <label class="form-label fw-semibold">{{ __('entries.number_of_checks') }}</label>
                                <input type="number" min="1" max="50" wire:model.live="checks_count" class="form-control">
                                @error('checks_count') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

                                <div class="small text-muted mt-2">
                                    {{ __('entries.auto_dates_hint') ?? 'Dates will be monthly from the entry date.' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-9">
                            <div class="p-3 rounded-4 border bg-white">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="fw-semibold">{{ __('entries.check_template') ?? 'Check Template' }}</div>
                                    <span class="badge text-bg-light text-muted">
                                        {{ __('entries.fill_once_hint') ?? 'Fill once then generate' }}
                                    </span>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('entries.customer') }}</label>
                                        <input class="form-control" wire:model="rows.0.customer" placeholder="...">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('entries.item') }}</label>
                                        <input class="form-control" wire:model="rows.0.item" placeholder="...">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('entries.amount') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₪</span>
                                            <input class="form-control" wire:model="rows.0.amount" inputmode="decimal">
                                        </div>
                                        @error('rows.0.amount') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">{{ __('entries.check_number') }}</label>
                                        <input class="form-control" wire:model="rows.0.check_number" placeholder="000123">
                                        @error('rows.0.check_number') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">{{ __('entries.bank') }}</label>
                                        <input class="form-control" wire:model="rows.0.bank_name" placeholder="Bank...">
                                        @error('rows.0.bank_name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">{{ __('entries.account_number') }}</label>
                                        <input class="form-control" wire:model="rows.0.account_number" placeholder="...">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">{{ __('entries.branch_number') }}</label>
                                        <input class="form-control" wire:model="rows.0.branch_number" placeholder="...">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">{{ __('entries.direction') }}</label>
                                        <select class="form-select" wire:model="rows.0.direction">
                                            <option value="in">{{ __('entries.in') }}</option>
                                            <option value="out">{{ __('entries.out') }}</option>
                                        </select>
                                        @error('rows.0.direction') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-9">
                                        <label class="form-label">{{ __('entries.attachment') }}</label>
                                        <input type="file" class="form-control" wire:model="rows.0.attachment">
                                        @error('rows.0.attachment') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    <button class="btn btn-primary px-4"
                                            wire:click="generateAndSaveChecks"
                                            @disabled($needsZeroBalance)>
                                        {{ __('entries.generate_and_save') }}
                                    </button>

                                    <div class="text-muted small d-flex align-items-center">
                                        {{ __('entries.after_generate_hint') ?? 'After generating, you can edit all rows.' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- STEP 2: Generated table (FULL fields) --}}
                @if($generated)
                    <div class="p-3 rounded-4 border bg-white">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                            <div>
                                <div class="fw-semibold">{{ __('entries.review_checks') ?? 'Review generated checks' }}</div>
                                <div class="small text-muted">
                                    {{ __('entries.generated_message') }}
                                </div>
                            </div>
                            <span class="badge text-bg-success">
                                {{ count($rows) }} {{ __('entries.checks_count_label') ?? 'checks' }}
                            </span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle text-center mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:50px;">#</th>
                                        <th style="min-width:180px;">{{ __('entries.customer') }}</th>
                                        <th style="min-width:160px;">{{ __('entries.item') }}</th>
                                        <th style="min-width:140px;">{{ __('entries.check_date') }}</th>
                                        <th style="min-width:150px;">{{ __('entries.check_number') }}</th>
                                        <th style="min-width:180px;">{{ __('entries.bank') }}</th>
                                        <th style="min-width:160px;">{{ __('entries.account_number') }}</th>
                                        <th style="min-width:140px;">{{ __('entries.branch_number') }}</th>
                                        <th style="min-width:140px;">{{ __('entries.amount') }}</th>
                                        <th style="min-width:130px;">{{ __('entries.direction') }}</th>
                                        <th style="min-width:240px;">{{ __('entries.attachment') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($rows as $i => $row)
                                        <tr>
                                            <td class="fw-semibold">{{ $i + 1 }}</td>

                                            <td><input class="form-control" wire:model="rows.{{ $i }}.customer"></td>
                                            <td><input class="form-control" wire:model="rows.{{ $i }}.item"></td>

                                            <td>
                                                <input type="date" class="form-control" wire:model="rows.{{ $i }}.check_date">
                                                @error("rows.$i.check_date") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                            </td>

                                            <td>
                                                <input class="form-control" wire:model="rows.{{ $i }}.check_number">
                                                @error("rows.$i.check_number") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                            </td>

                                            <td><input class="form-control" wire:model="rows.{{ $i }}.bank_name"></td>
                                            <td><input class="form-control" wire:model="rows.{{ $i }}.account_number"></td>
                                            <td><input class="form-control" wire:model="rows.{{ $i }}.branch_number"></td>

                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-text">₪</span>
                                                    <input class="form-control" wire:model="rows.{{ $i }}.amount" inputmode="decimal">
                                                </div>
                                                @error("rows.$i.amount") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                            </td>

                                            <td>
                                                <select class="form-select" wire:model="rows.{{ $i }}.direction">
                                                    <option value="in">{{ __('entries.in') }}</option>
                                                    <option value="out">{{ __('entries.out') }}</option>
                                                </select>
                                                @error("rows.$i.direction") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                            </td>

                                            <td class="text-start">
                                                <input type="file" class="form-control" wire:model="rows.{{ $i }}.attachment_file">

                                                @if(!empty($row['attachment']))
                                                    <div class="small text-muted mt-1">
                                                        {{ __('entries.current_attachment') }}:
                                                        <span class="text-truncate d-inline-block" style="max-width: 180px; vertical-align: bottom;">
                                                            {{ $row['attachment'] }}
                                                        </span>
                                                    </div>
                                                @endif

                                                @error("rows.$i.attachment_file") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Action bar --}}
                        <div class="d-flex flex-wrap justify-content-end gap-2 mt-3">
                            <a href="{{ route('entries.index') }}" class="btn btn-outline-secondary">
                                {{ __('general.cancel') }}
                            </a>

                            <button class="btn btn-success px-4" wire:click="updateGeneratedChecks">
                                {{ __('entries.update_and_finish') }}
                            </button>
                        </div>
                    </div>
                @endif

            @endif

            {{-- =========================
                 CASH/BANK (simple)
            ========================== --}}
            @if($payment_method === 'cash' || $payment_method === 'bank')
                <div class="p-3 rounded-4 border bg-white">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">{{ __('entries.amount') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">₪</span>
                                <input class="form-control" wire:model="rows.0.amount" inputmode="decimal">
                            </div>
                            @error('rows.0.amount') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-8 d-flex justify-content-end gap-2">
                            <a href="{{ route('entries.index') }}" class="btn btn-outline-secondary">
                                {{ __('general.cancel') }}
                            </a>
                            <button class="btn btn-success px-4" wire:click="saveSimple" @disabled($needsZeroBalance)>
                                {{ __('general.save') }}
                            </button>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- Zero Balance Modal --}}
    <div x-data="{ open: false }"
         x-on:open-zero-modal.window="open = true"
         x-on:close-zero-modal.window="open = false">

        <div x-show="open" style="background: rgba(0,0,0,.4); position:fixed; inset:0; z-index:9999;">
            <div class="shadow" style="background:#fff; max-width:520px; margin:10% auto; padding:20px; border-radius:16px;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">{{ __('entries.zero_title') }}</h5>
                    <button type="button" class="btn btn-sm btn-outline-secondary" x-on:click="open=false">×</button>
                </div>

                <div class="text-muted small mb-3">
                    {{ __('entries.zero_modal_hint') ?? '' }}
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('entries.zero_amount') }}</label>
                    <input type="number" step="0.01" wire:model="zero_amount" class="form-control">
                    @error('zero_amount') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary" x-on:click="open=false">
                        {{ __('general.cancel') }}
                    </button>

                    <button type="button" class="btn btn-primary" wire:click="saveZeroBalance">
                        {{ __('general.save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
