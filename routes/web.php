<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StakingController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Staking Routes
    Route::prefix('staking')->name('staking.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('active');
        Route::get('/', [StakingController::class, 'index'])->name('index');
        Route::get('/pool/{id}', [StakingController::class, 'show'])->name('show');
        Route::post('/pool/{id}/stake', [StakingController::class, 'stake'])->name('stake');
        Route::post('/{id}/claim', [StakingController::class, 'claimReward'])->name('claim');
    });
    
    // Wallet Routes
    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('index');
        Route::post('/create', [WalletController::class, 'createWallet'])->name('create');
        Route::get('/{coinType}/deposit', [WalletController::class, 'deposit'])->name('deposit');
        Route::get('/{coinType}/withdrawal', [WalletController::class, 'withdraw'])->name('withdraw');
        Route::post('/{coinType}/withdrawal', [WalletController::class, 'processWithdrawal'])->name('process-withdrawal');
        Route::post('/{coinType}/simulate-deposit', [WalletController::class, 'simulateDeposit'])->name('simulate-deposit');
    });
    
    // Transaction Routes
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/{id}', [TransactionController::class, 'show'])->name('show');
    });
});

// Authentication Routes (Laravel Breeze/UI)
require __DIR__.'/auth.php';