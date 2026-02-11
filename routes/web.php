<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('inicio');
});

Route::get('/inicio', [HomeController::class, 'inicio'])->name('inicio');