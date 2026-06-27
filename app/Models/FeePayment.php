<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeePayment extends Model
{
    /**
     * Each row here is ONE installment toward a student's fee.
     * total / paid / balance / status are NEVER stored — they are
     * always computed from FeeMaster::amount minus SUM(amount_paid)
     * for a given student_id + fee_master_id. See
     * FeePaymentController::buildSummary().
     */
    protected $fillable = [
        'student_id',
        'fee_master_id',
        'amount_paid',
        'payment_mode',
        'payment_date',
       
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount_paid'  => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feeMaster()
    {
        return $this->belongsTo(FeeMaster::class);
    }

    public function feeReceipt()
    {
        return $this->hasOne(FeeReceipt::class);
    }
}