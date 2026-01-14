<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Account;
use App\Models\CheckDetail;
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

   public function check()
    {
        return $this->hasMany(CheckDetails::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


