<?php

namespace App\Livewire\Admin\Tenants;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function migrate(string $tenantId)
    {
        Artisan::call('tenants:migrate', [
            '--tenants' => [$tenantId],
            '--path'    => 'database/migrations/tenant',
            '--force'   => true,
        ]);

        session()->flash('success', 'Tenant migrations executed');
    }

    public function toggle(string $tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);

        $active = $tenant->subscription_is_active; // ✅ accessor
        $tenant->update([
            'data->subscription->is_active' => ! $active,
        ]);

        session()->flash('success', 'Tenant status updated');
    }

    public function delete(string $tenantId)
    {
        Tenant::findOrFail($tenantId)->delete();
        session()->flash('success', 'Tenant deleted');
    }

    public function render()
    {
        $q = Tenant::query()->with('domains')->latest();

        if ($this->search !== '') {
            $s = Str::lower(trim($this->search));
            $q->whereRaw("LOWER(COALESCE(data->>'tenant_name','')) LIKE ?", ["%{$s}%"]);
        }

        return view('livewire.admin.tenants.index', [
            'tenants' => $q->paginate(10),
        ]);
    }
}
