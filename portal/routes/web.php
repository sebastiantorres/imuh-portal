<?php

use App\Http\Controllers\Portal\AuthController;
use App\Http\Controllers\Portal\PrincipalController;
use App\Http\Controllers\Portal\DocumentController;
use App\Http\Middleware\PortalAuthenticate;
use Illuminate\Support\Facades\Route;

Route::get('login', [AuthController::class, 'showLoginForm'])
    ->name('login');
Route::post('login', [AuthController::class, 'login']);

Route::middleware(PortalAuthenticate::class)->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/', [PrincipalController::class, 'index'])->name('portal.dashboard');
    Route::get('documents', [DocumentController::class, 'index'])->name('portal.documents');

    Route::post('documents/{requirement}/upload', [DocumentController::class, 'upload'])
        ->name('portal.documents.upload');

});
