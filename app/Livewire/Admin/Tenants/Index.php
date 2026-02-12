<?php

namespace App\Livewire\Admin\Tenants;

use App\Models\Tenant;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $q = '';

    public function render(): View
    {
        // $tenants = Tenant::query()
        //     ->with('meta', 'domains')
        //     ->when($this->q !== '', function ($query) {
        //         $q = $this->q;
        //         $query->whereHas('meta', fn($m) => $m->where('name', 'ilike', "%{$q}%")
        //                                          ->orWhere('owner_email', 'ilike', "%{$q}%"));
        //     })
        //     ->orderByDesc('created_at')
        //     ->paginate(20);

        $tenants = Tenant::query()
            ->with(['meta.owner', 'domains'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('livewire.admin.tenants.index', compact('tenants'));
    }
}
