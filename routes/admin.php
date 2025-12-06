<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BoatController;
use App\Http\Controllers\Admin\ProductController;

Route::apiResources([
    'boats' => BoatController::class,
    'products' => ProductController::class,
]);