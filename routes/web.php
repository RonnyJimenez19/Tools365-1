<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\PublicarController;

// ── Rutas públicas ────────────────────────────────────────────────────────────
Route::get('/',                  [HomeController::class, 'inicio'])->name('inicio');
Route::get('/buscar',            [HomeController::class, 'buscar'])->name('buscar');
Route::get('/busqueda-avanzada', [HomeController::class, 'busquedaAvanzada'])->name('busqueda.avanzada');

Route::get('/ofertas', [OfertaController::class, 'index'])->name('ofertas.index');
Route::view('/planes',   'planes.index')->name('planes.index');
Route::view('/contacto', 'contacto.index')->name('contacto.index');

Route::get( '/opiniones', [ComentarioController::class, 'index'])->name('comentarios.index');
Route::post('/opiniones', [ComentarioController::class, 'store'])->name('comentarios.store');

// ── Verificación de correo (públicas — el usuario NO tiene sesión) ─────────────
Route::get('/verificar/{token}',      [AuthController::class, 'verify'])->name('email.verify');
Route::view('/verificacion-pendiente', 'auth.verificacion-pendiente')->name('verificacion.pendiente');
Route::post('/reenviar-verificacion', [AuthController::class, 'reenviarVerificacion'])->name('verificacion.reenviar');

// ── Autenticación (solo para invitados) ───────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get( '/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register']);

    Route::get( '/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// ── Login admin ───────────────────────────────────────────────────────────────
Route::get( '/login-admin', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/login-admin', [AuthController::class, 'adminLogin']);

// ── Rutas protegidas (requieren sesión) ───────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get( '/publicar', [PublicarController::class, 'create'])->name('publicar.create');
    Route::post('/publicar', [PublicarController::class, 'store'])->name('publicar.store');

    Route::middleware('rol:admin,gerente')->group(function () {
        Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    });
});