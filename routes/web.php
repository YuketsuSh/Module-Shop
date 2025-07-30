<?php

use Illuminate\Support\Facades\Route;
use Modules\Shop\Http\Controllers\CategoryController;
use Modules\Shop\Http\Controllers\OrderController;
use Modules\Shop\Http\Controllers\ProductController;
use Modules\Shop\Http\Controllers\SettingsController;
use Modules\Shop\Http\Controllers\ShopController;
use Modules\Shop\Http\Controllers\TagController;

Route::prefix('admin/shop')->middleware(['web', 'auth'])->name('admin.shop.')->group(function () {

    Route::get('/', [ShopController::class, 'dashboard'])->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('tags', TagController::class);

    Route::resource('orders', OrderController::class)->only(['index', 'show', 'update']);

    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');

});
