<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:web');

Route::middleware(['web', 'auth:web'])->group(function () {
    /*
    * Wallets API
    *****************************/

    Route::get('/wallets', [WalletController::class, 'apiIndex'])->name('api.wallets.index');
    Route::post('/wallets', [WalletController::class, 'apiStore'])->name('api.wallets.store');
    Route::put('/wallets/{wallet}', [WalletController::class, 'apiUpdate'])->name('api.wallets.update');
    Route::delete('/wallets/{wallet}', [WalletController::class, 'apiDestroy'])->name('api.wallets.destroy');

    /*
    * Categories API
    *****************************/
    Route::get('/categories', [CategoryController::class, 'apiIndex'])->name('api.categories.index');
    Route::post('/categories', [CategoryController::class, 'apiStore'])->name('api.categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'apiUpdate'])->name('api.categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'apiDestroy'])->name('api.categories.destroy');
});
