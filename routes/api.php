<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PierController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductOptionController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('oauth/{provider}/callback', [AuthController::class, 'handleCallback']);

Route::get('/piers', [PierController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
Route::get('/products/{slug}/options/{optionId}', [ProductOptionController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
