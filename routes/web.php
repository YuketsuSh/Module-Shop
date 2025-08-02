<?php

use Illuminate\Support\Facades\Route;
use Modules\Shop\Http\Controllers\Admin\CategoryController;
use Modules\Shop\Http\Controllers\Admin\OrderController;
use Modules\Shop\Http\Controllers\Admin\ProductController;
use Modules\Shop\Http\Controllers\Admin\ProductVersionController;
use Modules\Shop\Http\Controllers\Admin\SettingsController;
use Modules\Shop\Http\Controllers\Admin\TagController;
use Modules\Shop\Http\Controllers\ShopController;

Route::prefix('admin/shop')->middleware(['web', 'auth'])->name('admin.shop.')->group(function () {

    Route::get('/', [ShopController::class, 'dashboard'])->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('tags', TagController::class);

    Route::resource('orders', OrderController::class)->only(['index', 'show', 'update']);

    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');

    Route::prefix('products/{product}/versions')->name('products.versions.')->group(function () {
        Route::get('/', [ProductVersionController::class, 'index'])->name('index');
        Route::get('create', [ProductVersionController::class, 'create'])->name('create');
        Route::post('/', [ProductVersionController::class, 'store'])->name('store');
        Route::get('{version}/edit', [ProductVersionController::class, 'edit'])->name('edit');
        Route::put('{version}', [ProductVersionController::class, 'update'])->name('update');
        Route::delete('{version}', [ProductVersionController::class, 'destroy'])->name('destroy');
        Route::get('{version}/download', [ProductVersionController::class, 'download'])->name('download');
    });

});
