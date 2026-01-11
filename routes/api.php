<?php

use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\WishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('products', [ProductController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/wishlist/{product}', [WishlistController::class, 'store']);
        Route::get('/wishlist', [WishlistController::class, 'index']);
        Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy']);
    });
});
