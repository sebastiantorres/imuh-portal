<?php

use App\Http\Controllers\Portal\AuthController;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\DocumentController;
use App\Http\Controllers\Portal\AccountController;
use App\Http\Middleware\PortalAuthenticate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Portal\VoucherController;

Route::get('login', [AuthController::class, 'showLoginForm'])
    ->name('login');
Route::post('login', [AuthController::class, 'login']);

Route::middleware(PortalAuthenticate::class)->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('portal.dashboard');
    Route::get('docs', [DocumentController::class, 'index'])->name('portal.documents');

    Route::post('documents/{requirement}/upload', [DocumentController::class, 'upload'])
        ->name('portal.documents.upload');
    Route::get('mi-cuenta', [AccountController::class, 'index'])
        ->name('portal.account')
        ->middleware('web');
    Route::post('vouchers/upload', [VoucherController::class, 'upload'])
        ->name('portal.voucher.upload')
        ->middleware('web');
    // Route::get('vouchers/form', [VoucherController::class, 'form'])
    //     ->name('portal.voucher.form')
    //     ->middleware('web');

    Route::get('vouchers/unassigned', function () {
        return view('portal.vouchers.unassigned');
    })->name('portal.voucher.unassigned.form')->middleware('web');

    Route::post('vouchers/unassigned', [VoucherController::class, 'uploadUnassigned'])
        ->name('portal.voucher.unassigned.upload')
        ->middleware('web');
    Route::get('/installment/voucher', [DashboardController::class, 'showVoucherForm'])
        ->name('portal.installment.voucher.form')
        ->middleware('web');
});
