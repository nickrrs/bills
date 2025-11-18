<?php

use App\Http\Controllers\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:web');

Route::middleware(['web', 'auth:web'])->group(function () {
    Route::get('/wallets', [WalletController::class, 'apiIndex'])->name('api.wallets.index');
    Route::post('/wallets', [WalletController::class, 'apiStore'])->name('api.wallets.store');
    Route::put('/wallets/{wallet}', [WalletController::class, 'apiUpdate'])->name('api.wallets.update');
    Route::delete('/wallets/{wallet}', [WalletController::class, 'apiDestroy'])->name('api.wallets.destroy');
});
