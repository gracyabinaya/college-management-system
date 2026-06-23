<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{protected $fillable = [
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
];

    protected $casts = [
        'date_of_birth' => 'date',
    ];
}
