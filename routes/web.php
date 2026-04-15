<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::resource('companies', CompanyController::class);
    Route::resource('users', UserController::class);

    Route::get('/', [TransactionController::class, 'index']);
    Route::post('/import', [TransactionController::class, 'import'])->name('transactions.import');
    Route::get('/export', [TransactionController::class, 'export'])->name('transactions.export');
});
