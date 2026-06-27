<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'student_id',
        'student_name',
        'age',
        'date_of_birth',
        'gender',
        'father_name',
        'last_class_studied',
        'previous_year_grade',
        'last_school_studied',
        'contact_number',
        'alternate_contact_number',
        'aadhaar_number',
        'photo',
        'course_id',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Student belongs to a Course.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Student has many Fee Payments.
     */
    public function feePayments()
    {
        return $this->hasMany(FeePayment::class);
    }

    /**
     * Student has many Fee Receipts.
     */
    public function feeReceipts()
    {
        return $this->hasMany(FeeReceipt::class);
    }

    

}