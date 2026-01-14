<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckDetails extends Model

{
   
    protected $fillable = [
        'entry_id',
        'customer',
        'item',
        'check_number',
        'bank_name',
        'account_number',
        'branch_number',
        'amount',
        'direction',
        'check_date',
        'attachment',
    ];

    public function entry()
    {
        return $this->belongsTo(Entry::class);
    }

}
