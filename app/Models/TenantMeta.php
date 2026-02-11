<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantMeta extends Model
{
    protected $table = 'tenant_meta';

    protected $primaryKey = 'tenant_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'tenant_id',
        'name',
        'type',
        'owner_user_id',
        'owner_email',
        'subscription_period',
        'subscription_ends_at',
        'is_active',
    ];

    protected $casts = [
        'subscription_ends_at' => 'date',
        'is_active' => 'boolean',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
