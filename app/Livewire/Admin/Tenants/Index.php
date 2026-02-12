<?php

namespace App\Livewire\Admin\Tenants;

use App\Models\Tenant;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

use App\Models\TenantMeta;
use Illuminate\Support\Carbon;

class Index extends Component
{
    use WithPagination;

    public $statusFilter = null;
    public $planFilter = null;
    public $search = null;

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


    public function suspend(string $tenantId)
    {
        $meta = TenantMeta::where('tenant_id', $tenantId)->first();

        if (!$meta) return;

        $meta->is_active = false;
        $meta->save();

        session()->flash('success', 'Subscription suspended.');
    }

    public function renew(string $tenantId)
    {
        $meta = TenantMeta::where('tenant_id', $tenantId)->first();

        if (!$meta) return;


        $meta->subscription_start_at = now()->toDateString();
        $meta->is_active = true;
        $meta->save();

        session()->flash('success', 'Subscription renewed.');
    }

    public function daysLeft(): ?int
    {
        $endsAt = $this->computedEndsAt();

        if (!$endsAt) {
            return null;
        }

        return now()->startOfDay()->diffInDays($endsAt->startOfDay(), false);
    }
}
