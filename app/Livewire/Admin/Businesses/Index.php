<?php

namespace App\Livewire\Admin\Businesses;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tenant;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function toggleActive(string $id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->update(['is_active' => ! $tenant->is_active]);
    }

    public function render()
    {
        $businesses = Tenant::query()
            ->with('owner:id,name,email')
            ->when($this->search, function ($q) {
                $q->where('business_name', 'like', "%{$this->search}%")
                  ->orWhereHas('owner', fn ($u) =>
                      $u->where('name','like',"%{$this->search}%")
                        ->orWhere('email','like',"%{$this->search}%")
                  );
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.businesses.index', compact('businesses'));
         
    }
}
