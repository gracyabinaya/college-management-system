<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('students', StudentController::class)->except(['destroy']);
Route::delete('students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
Route::get('students/{student}/print', [StudentController::class, 'print'])->name('students.print');
