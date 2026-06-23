<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Department;

class Course extends Model
{
    protected $fillable = [
        'course_code',
        'course_name',
        'department_id',  
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
