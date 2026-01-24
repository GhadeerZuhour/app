<?php

namespace App\Livewire\Entries;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Entry;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $type = '';
    public $account_id = null;

    // Month mode
    public string $month;        // 'YYYY-MM'
    public bool $showArchived = false;

   

public function mount()
{
    abort_unless(auth()->check(), 403);
    $this->month = now()->format('Y-m');
}

public function render()
{
    $start = Carbon::createFromFormat('Y-m', $this->month)->startOfMonth()->toDateString();
    $end   = Carbon::createFromFormat('Y-m', $this->month)->endOfMonth()->toDateString();

    $entries = Entry::query()
        ->where('tenant_id', tenant_id())
        ->when($this->account_id, fn($q) => $q->where('account_id', $this->account_id))
        ->where(function ($q) use ($start, $end) {
            $q->where('period', $this->month)
              ->orWhereBetween('entry_date', [$start, $end]);
        })
        ->when($this->showArchived === false, fn($q) => $q->where('is_archived', false))
        ->when($this->showArchived === true, fn($q) => $q->where('is_archived', true))
        ->when($this->search, function ($q) {
            $q->where(function ($qq) {
                $qq->where('reference_no', 'ilike', "%{$this->search}%")
                   ->orWhere('description', 'ilike', "%{$this->search}%");
            });
        })
        ->when($this->type, fn($q) => $q->where('payment_method', $this->type))
        ->latest('entry_date')
        
        ->paginate(10);
        

    return view('livewire.entries.index', compact('entries'));
}


}
