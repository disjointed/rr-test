<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdController;

Route::resource('ads', AdController::class);
