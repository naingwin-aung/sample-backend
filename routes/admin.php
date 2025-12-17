<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BoatController;
use App\Http\Controllers\Admin\PierController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\BoatTypeController;

Route::apiResources([
    'boat-types' => BoatTypeController::class,
    'piers' => PierController::class,
    'boats' => BoatController::class,
    'products' => ProductController::class,
]);

Route::get('all/piers', [PierController::class, 'allPiers']);
Route::get('all/boats', [BoatController::class, 'allBoats']);
Route::get('all/boat-types', [BoatTypeController::class, 'allBoatTypes']);