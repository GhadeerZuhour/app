<?php

namespace App\Livewire\Accounts;

use Livewire\Component;
use App\Models\Account;
use App\Models\Bank;
use App\Models\Currency;

class Create extends Component
{
    public array $banks = [];
    public array $currencies = [];

    public string $name = '';
    public string $type = 'bank';       // bank | cash
    public $bank_id = null;
    public $currency_id = null;
    public $balance = 0;
    public bool $is_active = true;

    public function mount(): void
    {
        $this->banks = Bank::orderBy('name')->get(['id', 'name', 'branch'])->toArray();
        $this->currencies = Currency::orderBy('code')->get(['id', 'code', 'name'])->toArray();

        // default currency (ILS if exists)
        $this->currency_id = Currency::where('code', 'ILS')->value('id') ?? Currency::value('id');
    }

    public function updatedType(): void
    {
        if ($this->type !== 'bank') {
            $this->bank_id = null; // cash account has no bank
        }
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:bank,cash',
            'bank_id' => $this->type === 'bank' ? 'required|exists:banks,id' : 'nullable',
            'currency_id' => 'required|exists:currencies,id',
            'balance' => 'required|numeric',
            'is_active' => 'boolean',
        ];
    }

    public function save()
    {
        $data = $this->validate();

        Account::create([
            'tenant_id' => tenant_id(),
            'name' => $data['name'],
            'type' => $data['type'],
            'bank_id' => $data['type'] === 'bank' ? $data['bank_id'] : null,
            'currency_id' => $data['currency_id'],
            'balance' => $data['balance'],
            'is_active' => $data['is_active'],
        ]);

        session()->flash('success', __('accounts.created_success'));
        return redirect()->route('accounts.index');
    }

    public function render()
    {
        return view('livewire.accounts.create');
    }
}
