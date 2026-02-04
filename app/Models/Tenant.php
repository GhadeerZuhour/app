<?php

namespace App\Models;


use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains, HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['id', 'data'];

    protected $casts = [
        'data' => 'array',
    ];

    public function getTenantNameAttribute()
    {
        return $this->data['tenant_name'] ?? null;
    }

    public function getTenantTypeAttribute()
    {
        return $this->data['tenant_type'] ?? null;
    }

    public function getIsActiveAttribute()
    {
        return $this->data['subscription']['is_active'] ?? false;
    }

    public function getSubscriptionPeriodAttribute()
    {
        return $this->data['subscription']['period'] ?? null;
    }

    public function getSubscriptionEndsAtAttribute()
    {
        $date = $this->data['subscription']['ends_at'] ?? null;

        return $date ? \Carbon\Carbon::parse($date) : null;
    }

    protected function endsAtLabel(): Attribute
    {
        return Attribute::get(function () {
            $raw = data_get($this->data, 'subscription.ends_at');

            // 1) stored
            if ($raw) {
                try {
                    return Carbon::parse($raw)->format('Y-m-d');
                } catch (\Throwable) {
                    // ignore
                }
            }

            // 2) calculated
            $base = $this->created_at ? Carbon::parse($this->created_at) : now();
            $period = data_get($this->data, 'subscription.period', 'monthly');

            $endsAt = $period === 'yearly'
                ? $base->copy()->addYear()
                : $base->copy()->addMonth();

            return $endsAt->format('Y-m-d');
        });
    }
    protected function name(): Attribute
    {
        return Attribute::get(fn() => data_get($this->data, 'tenant_name', '—'));
    }

    // Period: monthly|yearly
    protected function subscriptionPeriod(): Attribute
    {
        return Attribute::get(function () {
            $period = data_get($this->data ?? [], 'subscription.period');
            return in_array($period, ['monthly', 'yearly'], true) ? $period : 'monthly';
        });
    }


    // Is active default true
    protected function subscriptionIsActive(): Attribute
    {
        return Attribute::get(fn() => (bool) data_get($this->data, 'subscription.is_active', true));
    }

    // Ends At: from data OR calculated from created_at + period
    protected function subscriptionEndsAt(): Attribute
    {
        return Attribute::get(function () {
            $raw = data_get($this->data, 'subscription.ends_at');

            if ($raw) {
                try {
                    return Carbon::parse($raw);
                } catch (\Throwable) {
                    return null;
                }
            }

            // If not stored, calculate from created_at
            $base = $this->created_at ? Carbon::parse($this->created_at) : now();
            return match ($this->subscription_period) {
                'monthly' => $base->copy()->addMonth(),
                'yearly'  => $base->copy()->addYear(),
                default   => null,
            };
        });
    }



    // Status: ACTIVE | SUSPENDED | EXPIRED
    protected function subscriptionStatus(): Attribute
    {
        return Attribute::get(function () {
            $endsAt = $this->subscription_ends_at;

            if ($endsAt && now()->greaterThan($endsAt)) {
                return 'EXPIRED';
            }

            return $this->subscription_is_active ? 'ACTIVE' : 'SUSPENDED';
        });
    }

    // Domain (first domain)
    protected function domain(): Attribute
    {
        return Attribute::get(fn() => optional($this->domains->first())->domain);
    }


    public function getOwnerIdAttribute()
    {
        return $this->data['owner']['user_id'] ?? null;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
