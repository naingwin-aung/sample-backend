<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PierController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\ShoppingCartController;
use App\Http\Controllers\Api\ProductOptionController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('oauth/{provider}/callback', [AuthController::class, 'handleCallback']);

Route::get('/piers', [PierController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
Route::get('/products/{id}/related', [ProductController::class, 'related']);

Route::get('/products/{slug}/options/{optionId}', [ProductOptionController::class, 'index']);

Route::get('/shopping-carts', [ShoppingCartController::class, 'index']);
Route::post('/shopping-carts', [ShoppingCartController::class, 'create']); // Create a new shopping cart

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('checkout', [CheckoutController::class, 'index']);
    Route::post('checkout/confirm', [CheckoutController::class, 'confirm']);

    Route::post('/payment/confirm', [PaymentController::class, 'confirm']);

    // Booking
    Route::get('/bookings/{booking_number}', [BookingController::class, 'show']);
});

Route::get('/bookings/{booking_number}/voucher', [BookingController::class, 'voucher']);
Route::get('/bookings-calendar/{productId}', [BookingController::class, 'bookingsCalendar']);
