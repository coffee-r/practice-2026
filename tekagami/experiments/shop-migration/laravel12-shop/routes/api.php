<?php

use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/cart',                         [ShopController::class, 'cart']);
Route::post('/cart/items',                  [ShopController::class, 'addCartItem']);
Route::post('/checkout/quote',              [ShopController::class, 'checkoutQuote']);
Route::post('/orders',                      [ShopController::class, 'createOrder']);
Route::post('/orders/{orderId}/cancel',     [ShopController::class, 'cancelOrder']);
Route::post('/payments/credit/callback',    [ShopController::class, 'creditCallback']);
Route::get('/health',                       [ShopController::class, 'health']);
Route::post('/test/reset',                  [ShopController::class, 'reset']);
