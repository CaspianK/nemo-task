<?php

use App\Http\Controllers\AirportController;
use Illuminate\Support\Facades\Route;

Route::get('/search', [AirportController::class, 'search']);
