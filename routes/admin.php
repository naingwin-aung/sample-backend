<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PierController;
use App\Http\Controllers\ProductController;

Route::post('/', function () {
    return response()->json(['message' => 'Admin route works!']);
});