<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeMaster extends Model
{
    protected $fillable = [
        'department',
        'year',
        'category',
        'fee_name',
        'amount',
    ];

    public function feePayments()
    {
        return $this->hasMany(FeePayment::class);
    }
}