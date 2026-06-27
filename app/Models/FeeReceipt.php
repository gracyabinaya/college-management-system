<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeReceipt extends Model
{
    protected $fillable = [
        'receipt_number',
        'student_id',
        'fee_payment_id',
        'student_name',
        'fee_name',
        'amount',
        'payment_date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feePayment()
    {
        return $this->belongsTo(FeePayment::class);
    }

}