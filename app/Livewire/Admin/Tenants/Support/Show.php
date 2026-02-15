<?php

namespace App\Livewire\Tenant\Support;

use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Livewire\Component;

class Show extends Component
{
    public SupportTicket $ticket;
    public string $reply = '';

    public function mount(SupportTicket $ticket)
    {
        abort_unless(auth()->user()?->role === 'subscriber', 403);

        // حماية: التذكرة لازم تكون لنفس tenant + نفس user
        abort_unless($ticket->tenant_id === tenant('id'), 403);
        abort_unless($ticket->user_id === auth()->id(), 403);

        $this->ticket = $ticket->load(['messages.user']);
    }

    public function sendReply()
    {
        $this->validate(['reply' => 'required|min:2']);

        SupportMessage::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => auth()->id(),
            'message' => $this->reply,
        ]);

        // لو كانت resolved ورجع كتب → رجعها in_progress (اختياري مفيد)
        if ($this->ticket->status === 'resolved') {
            $this->ticket->update(['status' => 'in_progress', 'resolved_at' => null]);
        }

        $this->reply = '';
        $this->ticket->load('messages.user');
    }

    public function render()
    {
        return view('livewire.tenant.support.show');
    }
}
