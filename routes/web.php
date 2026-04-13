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

// Ofertas – paginado, público
Route::get('/ofertas', [OfertaController::class, 'index'])->name('ofertas.index');

// Planes – solo frontend estático
Route::view('/planes', 'planes.index')->name('planes.index');

// Contacto – solo frontend estático
Route::view('/contacto', 'contacto.index')->name('contacto.index');

// ── Comentarios (públicos para ver, cualquiera puede enviar) ──────────────────
Route::get( '/opiniones',  [ComentarioController::class, 'index'])->name('comentarios.index');
Route::post('/opiniones',  [ComentarioController::class, 'store'])->name('comentarios.store');

// ── Autenticación (solo para invitados) ───────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get( '/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register']);

    Route::get( '/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// ── Login admin (accesible sin sesión para poder loguearse) ──────────────────
Route::get( '/login-admin', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/login-admin', [AuthController::class, 'adminLogin']);

// ── Rutas protegidas (requieren sesión) ───────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Publicar herramienta
    Route::get( '/publicar', [PublicarController::class, 'create'])->name('publicar.create');
    Route::post('/publicar', [PublicarController::class, 'store'])->name('publicar.store');

    // Solo admin y gerente
    Route::middleware('rol:admin,gerente')->group(function () {
        Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
        // Aquí irán las rutas de CRUD de herramientas, usuarios, contenido
    });

    // TODO: rutas futuras del sistema
    // Route::get('/mis-publicaciones',  [...]);
    // Route::get('/mis-rentas',         [...]);
    // Route::get('/mis-compras',        [...]);
    // Route::get('/subastas',           [...]);
    // Route::get('/mensajes',           [...]);
    // Route::get('/mi-perfil',          [...]);
    // Route::get('/mis-favoritos',      [...]);
});