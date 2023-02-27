<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PageController;

Route::resource('/', HomeController::class);

Route::resource('/admin', AdminController::class);

Route::resource('/{slug}', PageController::class);
