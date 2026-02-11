<?php

namespace App\Livewire\Admin\Tenants;

use App\Models\Tenant;
use App\Models\TenantMeta;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Edit extends Component
{
    public Tenant $tenant;
    public TenantMeta $meta;

    public string $tenant_name = '';
    public ?string $tenant_type = null;

    public string $subscription_period = 'monthly';
    public ?string $subscription_ends_at = null;

    public bool $is_active = true;

    public function mount(string $tenantId): void
    {
        $this->tenant = Tenant::query()->with('meta')->findOrFail($tenantId);

        $this->meta = $this->tenant->meta
            ?? TenantMeta::create(['tenant_id' => $this->tenant->id, 'name' => '']);

        $this->tenant_name = $this->meta->name;
        $this->tenant_type = $this->meta->type;

        $this->subscription_period = $this->meta->subscription_period;
        $this->subscription_ends_at = optional($this->meta->subscription_ends_at)?->format('Y-m-d');
        $this->is_active = (bool) $this->meta->is_active;
    }

    public function update(): void
    {
        $this->validate([
            'tenant_name' => 'required|string|min:2',
            'subscription_period' => 'required|in:monthly,yearly',
        ]);

        $this->meta->update([
            'name' => $this->tenant_name,
            'type' => $this->tenant_type,
            'subscription_period' => $this->subscription_period,
            'subscription_ends_at' => $this->subscription_ends_at,
            'is_active' => (bool) $this->is_active,
        ]);

        session()->flash('success', 'Tenant updated.');
    }

    public function toggleActive(): void
    {
        $this->meta->update(['is_active' => ! $this->meta->is_active]);
        $this->is_active = (bool) $this->meta->is_active;

        session()->flash('success', $this->is_active ? 'Tenant activated.' : 'Tenant suspended.');
    }

    public function extend(int $days = 30): void
    {
        $base = $this->meta->subscription_ends_at
            ? Carbon::parse($this->meta->subscription_ends_at)
            : now();

        $new = $base->addDays($days)->format('Y-m-d');

        $this->meta->update(['subscription_ends_at' => $new]);
        $this->subscription_ends_at = $new;

        session()->flash('success', "Subscription extended to {$new}.");
    }

    public function render(): View
    {
        return view('livewire.admin.tenants.edit');
    }
}
