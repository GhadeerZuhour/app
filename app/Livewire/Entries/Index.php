<?php


namespace App\Livewire\Entries;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Entry;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $type = '';
    public $fromDate;
    public $toDate;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $entries = Entry::query()
            ->when($this->search, function ($q) {
                $q->where('reference_no', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
            })
            ->when($this->type, fn ($q) => $q->where('type', $this->type))
            ->when($this->fromDate, fn ($q) => $q->whereDate('entry_date', '>=', $this->fromDate))
            ->when($this->toDate, fn ($q) => $q->whereDate('entry_date', '<=', $this->toDate))
            ->latest()
            ->paginate(10);

        return view('livewire.entries.index', compact('entries'));
    }
}
