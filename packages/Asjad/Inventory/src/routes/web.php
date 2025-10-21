<?php
use Illuminate\Support\Facades\Route;
use Asjad\Inventory\Http\Controllers\ProductController;

Route::prefix('inventory')->group(function () {
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/list', [ProductController::class, 'list']);
    Route::post('products/store', [ProductController::class, 'store']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);
});
