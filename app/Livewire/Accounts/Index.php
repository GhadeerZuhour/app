<?php

namespace App\Livewire\Accounts;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Account;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';
    public string $type = '';      // bank | cash
    public string $status = '';    // active | inactive

    public function updated($field)
    {
        if (in_array($field, ['search', 'type', 'status'])) {
            $this->resetPage();
        }
    }

    public function toggleStatus($id)
    {
        $account = Account::where('tenant_id', tenant_id())->findOrFail($id);
        $account->is_active = ! $account->is_active;
        $account->save();
    }

    public function render()
    {
        $accounts = Account::where('tenant_id', tenant_id())
            ->with(['bank', 'currency'])
            ->when($this->search, fn ($q) =>
                $q->where('name', 'ilike', "%{$this->search}%")
            )
            ->when($this->type, fn ($q) =>
                $q->where('type', $this->type)
            )
            ->when($this->status !== '', fn ($q) =>
                $q->where('is_active', $this->status === 'active')
            )
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.accounts.index', compact('accounts'));
    }
}
