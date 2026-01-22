<?php

namespace App\Models;

use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    use HasDomains, HasDatabase;
    protected $fillable = [
        'id', // UUID by default
        'user_id',
        'business_name',
        'business_type',
        'business_phone',
        'business_address',
        'subscription_period',
        'subscription_ends_at',
        'is_active',
        'data',
    ];

    protected $casts = [
        'subscription_ends_at' => 'date',
        'is_active' => 'boolean',
        'data' => 'array',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
