<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;

Route::get('image/{filename}', [ApplicationController::class, 'image'])->where('filename', '.*');