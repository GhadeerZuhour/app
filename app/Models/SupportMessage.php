<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

class SupportMessage extends Model
{
    use CentralConnection;

    protected $fillable = [
        'ticket_id',
        'sender_type',
        'admin_user_id',
        'tenant_user_id',
        'sender_name',
        'sender_email',
        'message',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_user_id'); // admin only
    }

    public function senderLabel(): string
    {
        if ($this->sender_type === 'admin') {
            return $this->admin?->name ?? 'Admin';
        }
        return $this->sender_name ?: 'Tenant User';
    }
}
