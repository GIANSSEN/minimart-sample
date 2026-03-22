<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\SaleApiController;
use App\Http\Controllers\Api\StockApiController;

Route::prefix('v1')->group(function () {
    // Product API
    Route::get('/products/search', [ProductApiController::class, 'search']);
    Route::get('/products/{barcode}', [ProductApiController::class, 'show']);
    Route::get('/products/category/{categoryId}', [ProductApiController::class, 'byCategory']);
    
    // Sale API
    Route::post('/sales', [SaleApiController::class, 'store']);
    Route::get('/sales/today', [SaleApiController::class, 'todaySales']);
    Route::get('/sales/receipt/{receiptNo}', [SaleApiController::class, 'getByReceipt']);
    
    // Stock API
    Route::get('/stock/low-stock', [StockApiController::class, 'lowStock']);
    Route::get('/stock/product/{productId}', [StockApiController::class, 'getProductStock']);
    Route::post('/stock/check', [StockApiController::class, 'checkAvailability']);
});