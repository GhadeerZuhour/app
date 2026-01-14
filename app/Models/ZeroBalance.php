<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZeroBalance extends Model
{
    protected $fillable = [
        'tenant_id',
        'account_id',
        'month',
        'zero_amount',
    ];
}
