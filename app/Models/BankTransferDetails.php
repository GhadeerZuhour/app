<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankTransferDetails extends Model

{
    protected $fillable = [
        'entry_id',
        'from_account_id',
        'to_account_id',
        'reference_number',
        'transfer_date',
    ];

    public function entry()
    {
        return $this->belongsTo(Entry::class);
    }

}
