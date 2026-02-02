<?php

namespace App\Livewire\Admin\Tenants;

use App\Models\Tenant;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Show extends Component
{
    public Tenant $tenant;

    public ?string $domain = null;

    public function mount(Tenant $tenant): void
    {
        $this->tenant = $tenant->load('domains');
        $this->domain = $this->tenant->domains->first()?->domain;
    }

    public function render(): View
    {
        return view('livewire.admin.tenants.show');
    }
}
