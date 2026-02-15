<?php

namespace App\Livewire\Admin\Support;

use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Livewire\Component;

class Show extends Component
{
    public SupportTicket $ticket;
    public string $reply = '';
    public string $status = '';

    public function mount(SupportTicket $ticket)
    {
        abort_unless(auth()->user()?->role === 'admin', 403);

        $this->ticket = $ticket->load(['owner', 'assignee', 'messages.user']);
        $this->status = $this->ticket->status;
    }

    public function assignToMe()
    {
        $this->ticket->update(['assigned_to' => auth()->id()]);
        $this->ticket->refresh();
    }

    public function updateStatus()
    {
        $this->ticket->update([
            'status' => $this->status,
            'resolved_at' => $this->status === 'resolved' ? now() : null,
        ]);
        $this->ticket->refresh();
    }

    public function sendReply()
    {
        $this->validate(['reply' => 'required|min:2']);

        SupportMessage::create([
            'ticket_id' => $this->ticket->id,
            'sender_type' => 'admin',
            'admin_user_id' => auth()->id(),
            'sender_name' => auth()->user()->name,
            'sender_email' => auth()->user()->email,
            'message' => $this->reply,
        ]);


        // أول رد أدمن => in_progress
        if ($this->ticket->status === 'new') {
            $this->ticket->update(['status' => 'in_progress']);
            $this->status = 'in_progress';
        }

        $this->reply = '';
        $this->ticket->load('messages.user');
    }

    public function render()
    {
        return view('livewire.admin.support.show');
    }
}
