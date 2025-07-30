<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin/shop')->middleware(['web', 'auth'])->group(function () {
    Route::get('/', [\Modules\Shop\Http\Controllers\ShopController::class, 'index'])->name('admin.shop.index');
});