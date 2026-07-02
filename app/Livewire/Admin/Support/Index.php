<?php

namespace App\Livewire\Admin\Support;

use App\Models\SupportTicket;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $status = '';
    public string $type = '';
    public string $priority = '';
    public string $q = '';

    protected $queryString = [
        'status' => ['except' => ''],
        'type' => ['except' => ''],
        'priority' => ['except' => ''],
        'q' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function updated($property): void
    {
        if (in_array($property, ['status','type','priority','q'], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['status','type','priority','q']);
        $this->resetPage();
    }

    public function render()
    {
        $rows = SupportTicket::query()
            ->when($this->status, fn($qq) => $qq->where('status', $this->status))
            ->when($this->type, fn($qq) => $qq->where('type', $this->type))
            ->when($this->priority, fn($qq) => $qq->where('priority', $this->priority))
            ->when($this->q, function ($qq) {
                $term = trim($this->q);
                $qq->where(function ($w) use ($term) {
                    $w->where('subject', 'ilike', "%{$term}%")
                      ->orWhere('requester_name', 'ilike', "%{$term}%")
                      ->orWhere('requester_email', 'ilike', "%{$term}%");
                });
            })
            ->latest()
            ->paginate(12);

        $counts = [
            'new' => SupportTicket::where('status','new')->count(),
            'in_progress' => SupportTicket::where('status','in_progress')->count(),
            'resolved' => SupportTicket::where('status','resolved')->count(),
            'renewal' => SupportTicket::where('type','renewal')->whereIn('status',['new','in_progress'])->count(),
        ];

        return view('livewire.admin.support.index', [
            'rows' => $rows,
            'counts' => $counts,
        ]);
    }
}
