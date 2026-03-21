<?php

// ─── AGREGAR ESTAS LÍNEAS A routes/web.php ─────────────────────────────────
// Asegúrate de importar el controlador al inicio del archivo:
// use App\Http\Controllers\AuthController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;

// Rutas públicas existentes
Route::get('/', [HomeController::class, 'inicio'])->name('inicio');
Route::get('/buscar', [HomeController::class, 'buscar'])->name('buscar');
Route::get('/busqueda-avanzada', [HomeController::class, 'busquedaAvanzada'])->name('busqueda.avanzada');

// ── Autenticación (solo para invitados) ──────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/registro',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register']);

    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// ── Rutas protegidas (requieren sesión) ──────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

    // TODO: rutas futuras del sistema
    // Route::get('/mis-publicaciones',  [...]);
    // Route::get('/publicar',           [...]);
    // Route::get('/mis-rentas',         [...]);
    // Route::get('/mis-compras',        [...]);
    // Route::get('/subastas',           [...]);
    // Route::get('/mensajes',           [...]);
    // Route::get('/mi-perfil',          [...]);
    // Route::get('/mis-favoritos',      [...]);
});