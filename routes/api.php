<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\InventoryController;

Route::get('/', function () {
    return response()->json([
        'message' => 'Connected to API',
    ]);
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [UserController::class, 'register'])->name('register');
    Route::post('/login', [UserController::class, 'login'])->name('login');
}); 

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::post('/products/update-stock', [ProductController::class, 'updateStock']);
    Route::get('/products/{id}', [ProductController::class, 'show']);

    Route::middleware('admin')->group(function () {
        Route::post('/categories', [CategoryController::class, 'store']);
        
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);
        
        Route::prefix('inventory')->group(function () {
            Route::get('/value', [InventoryController::class, 'valueInventory']);
        });
    });
});
