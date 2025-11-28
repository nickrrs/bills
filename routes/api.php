<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\Settings\SettingsController;
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
    Route::delete('/wallets/bulk', [WalletController::class, 'apiBulkDestroy'])->name('api.wallets.bulk.destroy');
    Route::put('/wallets/{wallet}', [WalletController::class, 'apiUpdate'])->name('api.wallets.update');
    Route::delete('/wallets/{wallet}', [WalletController::class, 'apiDestroy'])->name('api.wallets.destroy');

    /*
    * Categories API
    *****************************/
    Route::get('/categories', [CategoryController::class, 'apiIndex'])->name('api.categories.index');
    Route::get('/categories/{category}', [CategoryController::class, 'apiShow'])->name('api.categories.show');
    Route::get('/categories/{category}/page', [CategoryController::class, 'apiGetCategoryPage'])->name('api.categories.page');
    Route::post('/categories', [CategoryController::class, 'apiStore'])->name('api.categories.store');
    Route::delete('/categories/bulk', [CategoryController::class, 'apiBulkDestroy'])->name('api.categories.bulk.destroy');
    Route::put('/categories/{category}', [CategoryController::class, 'apiUpdate'])->name('api.categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'apiDestroy'])->name('api.categories.destroy');

    /*
    * Subcategories API
    *****************************/
    Route::get('/subcategories', [SubcategoryController::class, 'apiIndex'])->name('api.subcategories.index');
    Route::post('/subcategories', [SubcategoryController::class, 'apiStore'])->name('api.subcategories.store');
    Route::delete('/subcategories/bulk', [SubcategoryController::class, 'apiBulkDestroy'])->name('api.subcategories.bulk.destroy');
    Route::put('/subcategories/{subcategory}', [SubcategoryController::class, 'apiUpdate'])->name('api.subcategories.update');
    Route::delete('/subcategories/{subcategory}', [SubcategoryController::class, 'apiDestroy'])->name('api.subcategories.destroy');

    /*
    * Settings API
    *****************************/

    Route::get('/settings', [SettingsController::class, 'apiIndex'])->name('api.settings.index');
    Route::post('/settings', [SettingsController::class, 'apiStore'])->name('api.settings.store');
});
