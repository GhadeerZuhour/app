<?php

namespace App\Livewire\Tenant\Support;

use App\Models\SupportTicket;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $status = '';
    public string $type = '';

    public function render()
    {
        abort_unless(auth()->user()?->role === 'subscriber', 403);

        $tenantId = tenant('id'); // stancl helper

        $tickets = SupportTicket::query()
            ->where('tenant_id', tenant('id'))
            ->where('requester_tenant_user_id', auth()->id())
            ->latest()
            ->paginate(10);


        return view('livewire.tenant.support.index', compact('tickets'));
    }
}
