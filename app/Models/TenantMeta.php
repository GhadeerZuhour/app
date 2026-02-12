<?php

namespace App\Models;

use Illuminate\Support\Carbon;
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
        'subscription_start_at',
        'is_active',
    ];

    protected $casts = [
        'subscription_start_at' => 'date',

        'is_active' => 'boolean',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function computedEndsAt(): ?Carbon
    {

        if (!$this->subscription_start_at) {
            return null; 
        }


        $start = $this->subscription_start_at instanceof Carbon
            ? $this->subscription_start_at
            : Carbon::parse($this->subscription_start_at);

        return match ($this->subscription_period) {
            'monthly'     => $start->copy()->addMonth(),
            'quarterly'   => $start->copy()->addMonths(3),
            'semiannual'  => $start->copy()->addMonths(6),
            'yearly'      => $start->copy()->addYear(),
            default       => $start->copy()->addMonth(),
        };
    }
}
