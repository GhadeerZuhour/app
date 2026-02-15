<?php

namespace App\Livewire\Tenant\Support;

use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Livewire\Component;

class Create extends Component
{
    public string $type = 'general';
    public string $priority = 'normal';
    public string $subject = '';
    public string $description = '';

    // renewal meta
    public int $months = 1;
    public string $payment_method = 'bank'; // bank|cash|stripe (later)

    public function save()
    {
        abort_unless(auth()->user()?->role === 'subscriber', 403);

        $this->validate([
            'type' => 'required|in:general,renewal,bug,invoice',
            'priority' => 'required|in:low,normal,high',
            'subject' => 'required|min:3',
            'description' => 'nullable',
            'months' => 'required_if:type,renewal|in:1,3,6,12',
            'payment_method' => 'required_if:type,renewal|in:bank,cash,stripe',
        ]);

        $meta = null;
        if ($this->type === 'renewal') {
            $meta = [
                'months' => $this->months,
                'payment_method' => $this->payment_method,
            ];
        }

        $tenantUser = auth()->user();

        $ticket = SupportTicket::create([
            'tenant_id' => tenant('id'),
            'requester_tenant_user_id' => auth()->id(),
            'requester_name' => $tenantUser->name,
            'requester_email' => $tenantUser->email,

            'type' => $this->type,
            'priority' => $this->priority,
            'subject' => $this->subject,
            'description' => $this->description,
            'meta' => $meta,
            'status' => 'new',
        ]);

        // أول رسالة (اختياري)
        if (trim($this->description) !== '') {
            SupportMessage::create([
                'ticket_id' => $ticket->id,
                'sender_type' => 'tenant_user',
                'tenant_user_id' => auth()->id(),
                'sender_name' => $tenantUser->name,
                'sender_email' => $tenantUser->email,
                'message' => $this->description,
            ]);
        }


        return $this->redirectRoute('tenant.support.show', $ticket->id);
    }

    public function render()
    {
        return view('livewire.tenant.support.create');
    }
}
