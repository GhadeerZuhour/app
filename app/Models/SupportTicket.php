<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

class SupportTicket extends Model
{
    use HasUuids, CentralConnection;

    protected $fillable = [
        'tenant_id',
        'requester_tenant_user_id',
        'requester_name',
        'requester_email',
        'assigned_to',
        'type',
        'status',
        'priority',
        'subject',
        'description',
        'meta',
        'resolved_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'resolved_at' => 'datetime',
    ];

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to'); // admin only
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SupportMessage::class, 'ticket_id')->latest();
    }
}
