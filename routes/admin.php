<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BoatController;

Route::apiResources([
    'boats' => BoatController::class,
]);