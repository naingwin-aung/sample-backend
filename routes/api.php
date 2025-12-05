<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PierController;
use App\Http\Controllers\ProductController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('oauth/{provider}/callback', [AuthController::class, 'handleCallback']);

Route::get('/piers', [PierController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
