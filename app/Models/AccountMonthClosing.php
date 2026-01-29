<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountMonthClosing extends Model
{
    protected $fillable = [
        'tenant_id',
        'account_id',
        'period',
        'opening',
        'total_in',
        'total_out',
        'closing',
        'archived_at',
    ];
}
