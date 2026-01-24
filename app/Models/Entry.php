<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Account;
use App\Models\CheckDetails;
use App\Models\User;    

class Entry extends Model
{
    protected $fillable = [
        'tenant_id',
        'account_id',
        'user_id',
        'payment_method',
        'total_amount',
        'entry_date',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function checks()
    {
        return $this->hasMany(CheckDetails::class);
    }

    public function check()
    {
        return $this->checks();
    }

    public function bankTransfer()
{
    return $this->hasOne(\App\Models\BankTransferDetails::class, 'entry_id');
}

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   protected static function booted()
{
    static::creating(function ($entry) {
        if (empty($entry->period) && !empty($entry->entry_date)) {
            $entry->period = \Carbon\Carbon::parse($entry->entry_date)->format('Y-m');
        }
        if (empty($entry->tenant_id)) {
            $entry->tenant_id = tenant_id();
        }
    });
}

}

