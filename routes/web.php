<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeeMasterController;
use App\Http\Controllers\FeePaymentController;
use App\Http\Controllers\FeeReceiptController;

use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EnrollmentController;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Fee Management
|--------------------------------------------------------------------------
*/

Route::resource('fee-masters', FeeMasterController::class);

Route::resource('fee-payments', FeePaymentController::class);

Route::resource('fee-receipts', FeeReceiptController::class);

Route::get('/fee-receipts/{id}/download', [FeeReceiptController::class, 'download'])
    ->name('fee-receipts.download');

Route::get('/fees/pending', [FeePaymentController::class, 'pending'])
    ->name('fees.pending');

Route::get('/fees/paid', [FeePaymentController::class, 'paid'])
    ->name('fees.paid');

Route::get('/fees/dashboard', [DashboardController::class, 'index'])
    ->name('fees.dashboard');

/*
|--------------------------------------------------------------------------
| Course Management
|--------------------------------------------------------------------------
*/

Route::resource('courses', CourseController::class);

/*
|--------------------------------------------------------------------------
| Department Management
|--------------------------------------------------------------------------
*/

Route::resource('departments', DepartmentController::class);

/*
|--------------------------------------------------------------------------
| Student Management
|--------------------------------------------------------------------------
*/

Route::resource('students', StudentController::class)->except(['destroy']);

Route::delete('students/{student}', [StudentController::class, 'destroy'])
    ->name('students.destroy');

Route::get('students/{student}/print', [StudentController::class, 'print'])
    ->name('students.print');

/*
|--------------------------------------------------------------------------
| Enrollment Management
|--------------------------------------------------------------------------
*/

Route::get('/enrollments', [EnrollmentController::class, 'index'])
    ->name('enrollments.index');

Route::get('/enrollments/create', [EnrollmentController::class, 'create'])
    ->name('enrollments.create');

Route::post('/enrollments', [EnrollmentController::class, 'store'])
    ->name('enrollments.store');

Route::delete('/enrollments/{id}', [EnrollmentController::class, 'destroy'])
    ->name('enrollments.destroy');