<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DepartmentController;

Route::get('/', function () {
    return view('welcome');
});
// Courses
Route::resource('courses', CourseController::class);

// Departments
Route::resource('departments', DepartmentController::class);

// Students (resource without destroy)
Route::resource('students', StudentController::class)->except(['destroy']);

// Custom student routes
Route::delete('students/{student}', [StudentController::class, 'destroy'])
    ->name('students.destroy');

Route::get('students/{student}/print', [StudentController::class, 'print'])
    ->name('students.print');
