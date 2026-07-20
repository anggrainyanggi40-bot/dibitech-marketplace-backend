<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\CartController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::apiResource('categories', ProductCategoryController::class)->only(['index','show']);
Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/midtrans/notification', [PaymentController::class, 'notification']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/seller/products', [ProductController::class, 'sellerProducts']);
    Route::apiResource('products', ProductController::class)->only(['store', 'update', 'destroy']);

    Route::apiResource('categories', ProductCategoryController::class)->only(['store','update','destroy']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::patch('/orders/{id}/cancel', [OrderController::class, 'cancel']);
    Route::post('/orders/{orderId}/payment', [PaymentController::class,'createPayment']);

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::post('/become-seller', [UserController::class, 'becomeSeller']);

    Route::get('/carts', [CartController::class, 'index']);
    Route::post('/carts', [CartController::class, 'store']);
    Route::delete('/carts/{id}', [CartController::class, 'destroy']);
    Route::post('/orders/checkout-cart', [OrderController::class, 'checkoutCart']);
});



