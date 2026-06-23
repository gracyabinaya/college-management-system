<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Course;

class Enrollment extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
    ];
public function student()
{
    return $this->belongsTo(Student::class, 'student_id');
}

public function course()
{
    return $this->belongsTo(Course::class, 'course_id');
}
}
