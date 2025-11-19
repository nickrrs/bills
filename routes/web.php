<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('wallets', [WalletController::class, 'index'])->middleware(['auth', 'verified'])->name('wallets');
Route::get('wallets/create', [WalletController::class, 'create'])->middleware(['auth', 'verified'])->name('wallets.create');
Route::post('wallets', [WalletController::class, 'store'])->middleware(['auth', 'verified'])->name('wallets.store');
Route::get('categories', [CategoryController::class, 'index'])->middleware(['auth', 'verified'])->name('categories');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
