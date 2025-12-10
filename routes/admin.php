<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BoatController;
use App\Http\Controllers\Admin\PierController;
use App\Http\Controllers\Admin\ProductController;

Route::apiResources([
    'piers' => PierController::class,
    'boats' => BoatController::class,
    'products' => ProductController::class,
]);