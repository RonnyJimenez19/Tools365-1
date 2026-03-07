<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'inicio'])->name('inicio');
Route::get('/buscar', [HomeController::class, 'buscar'])->name('buscar');
Route::get('/busqueda-avanzada', [HomeController::class, 'busquedaAvanzada'])->name('busqueda.avanzada');