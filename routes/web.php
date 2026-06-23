<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeeMasterController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('fee-masters', FeeMasterController::class);
Route::get('/fees/dashboard', [DashboardController::class, 'index']);

use App\Http\Controllers\FeePaymentController;

Route::resource('fee-payments', FeePaymentController::class);

use App\Http\Controllers\FeeReceiptController;

Route::resource('fee-receipts', FeeReceiptController::class);
Route::get('/fee-receipts/{id}/download', [FeeReceiptController::class, 'download'])
    ->name('fee-receipts.download');

Route::get('/fees/pending', [FeePaymentController::class, 'pending']);

Route::get('/fees/paid', [FeePaymentController::class, 'paid']);

use App\Http\Controllers\DashboardController;

Route::get('/fees/dashboard', [DashboardController::class, 'index']);