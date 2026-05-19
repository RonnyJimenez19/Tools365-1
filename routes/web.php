<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\PublicarController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\SubastaController;
use App\Http\Controllers\PlanController;



// ── Rutas públicas ────────────────────────────────────────────────────────────
Route::get('/',                  [HomeController::class, 'inicio'])->name('inicio');
Route::get('/buscar',            [HomeController::class, 'buscar'])->name('buscar');
Route::get('/busqueda-avanzada', [HomeController::class, 'busquedaAvanzada'])->name('busqueda.avanzada');

Route::get('/productos/{producto}', [ProductoController::class, 'show'])->name('productos.show');

Route::get('/ofertas', [OfertaController::class, 'index'])->name('ofertas.index');
Route::view('/planes',   'planes.index')->name('planes.index');
Route::view('/contacto', 'contacto.index')->name('contacto.index');

Route::get( '/opiniones', [ComentarioController::class, 'index'])->name('comentarios.index');
Route::post('/opiniones', [ComentarioController::class, 'store'])->name('comentarios.store');

// ── Verificación de correo ────────────────────────────────────────────────────
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
Route::middleware(['auth','cuenta.activa'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ── Publicar ─────────────────────────────────────────────────────────────
    Route::get('/publicar', [PublicarController::class, 'create'])->name('publicar.create');
    Route::post('/publicar', [PublicarController::class, 'store'])->name('publicar.store');

    Route::prefix('mis-publicaciones')->name('mis-publicaciones.')->group(function () {
        Route::get('/',                    [PublicarController::class, 'index'])->name('index');
        Route::get('/{producto}',          [PublicarController::class, 'show'])->name('show');
        Route::get('/{producto}/editar',   [PublicarController::class, 'edit'])->name('edit');
        Route::put('/{producto}',          [PublicarController::class, 'update'])->name('update');
        Route::patch('/{producto}/estado', [PublicarController::class, 'cambiarEstado'])->name('estado');
        Route::delete('/{producto}',       [PublicarController::class, 'destroy'])->name('destroy');
    });

 Route::prefix('subastas')->name('subastas.')->group(function () {
    Route::get('/',                              [SubastaController::class, 'index'])        ->name('index');
    Route::post('/{producto}/pujar',             [SubastaController::class, 'pujar'])        ->name('pujar');
    Route::get('/{producto}/historial',          [SubastaController::class, 'historial'])    ->name('historial');
    Route::delete('/{producto}/cancelar',        [SubastaController::class, 'cancelar'])     ->name('cancelar');
    Route::patch('/{producto}/vender',           [SubastaController::class, 'vender'])       ->name('vender');
 
    // Pago de subasta ganada (flujo separado del carrito)
    Route::get('/pagar/{pedido}',                [SubastaController::class, 'iniciarPago'])  ->name('pagar');
    Route::post('/pagar/{pedido}/procesar',      [SubastaController::class, 'procesarPago']) ->name('pagar.procesar');
    Route::get('/pagar/{pedido}/timer',          [SubastaController::class, 'timerSubasta']) ->name('pagar.timer');
});

// ── Plan / Suscripción ────────────────────────────────────────────────────
Route::prefix('dashboard/plan')->name('dashboard.plan')->group(function () {
    Route::get('/',                    [PlanController::class, 'index'])   ->name('');         // GET  /dashboard/plan
    Route::get('/checkout/{plan}',     [PlanController::class, 'checkout'])->name('.checkout'); // GET  /dashboard/plan/checkout/pro
    Route::post('/pagar',              [PlanController::class, 'pagar'])   ->name('.pagar');    // POST /dashboard/plan/pagar
    Route::delete('/cancelar',         [PlanController::class, 'cancelar'])->name('.cancelar'); // DEL  /dashboard/plan/cancelar
});

// Rentas
Route::get('/dashboard/rentas', [\App\Http\Controllers\RentasController::class, 'index'])->name('dashboard.rentas');
Route::patch('/dashboard/rentas/{pedidoItem}/reactivar', [\App\Http\Controllers\RentasController::class, 'reactivar'])->name('dashboard.rentas.reactivar');

    });
 

    // ── Carrito ───────────────────────────────────────────────────────────────
    Route::prefix('carrito')->name('carrito.')->group(function () {
        Route::get('/',                [CarritoController::class, 'index'])   ->name('index');
        Route::post('/',               [CarritoController::class, 'store'])   ->name('store');
        Route::patch('/{carritoItem}', [CarritoController::class, 'update'])  ->name('update');
        Route::delete('/{carritoItem}',[CarritoController::class, 'destroy']) ->name('destroy');
        Route::delete('/',             [CarritoController::class, 'vaciar'])  ->name('vaciar');
        Route::get('/checkout',        [CarritoController::class, 'checkout'])->name('checkout');
        Route::get('/conteo',          [CarritoController::class, 'conteo'])  ->name('conteo');
    });

    // ── Pago ─────────────────────────────────────────────────────────────────
    Route::prefix('pago')->name('pago.')->group(function () {
        Route::get('/iniciar',                    [PagoController::class, 'iniciar'])       ->name('iniciar');
        Route::post('/procesar',                  [PagoController::class, 'procesar'])      ->name('procesar');
        Route::get('/timer-status',               [PagoController::class, 'timerStatus'])   ->name('timer.status');
        Route::get('/{pedido}/confirmacion',      [PagoController::class, 'confirmacion'])  ->name('confirmacion');
        Route::delete('/tarjetas/{tarjeta}',      [PagoController::class, 'eliminarTarjeta'])->name('tarjetas.destroy');
    });

    // ── Dashboard ─────────────────────────────────────────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Ventas (vendedor) y Compras (comprador) — ambas accesibles para todos
    Route::get('/dashboard/ventas',  [VentasController::class, 'index'])  ->name('dashboard.ventas');
    Route::get('/dashboard/compras', [VentasController::class, 'compras'])->name('dashboard.compras');

    // Notificaciones
    Route::prefix('dashboard/notificaciones')->name('notificaciones.')->group(function () {
        Route::get('/',                              [NotificacionController::class, 'index'])           ->name('index');
        Route::post('/leer-todas',                   [NotificacionController::class, 'marcarTodasLeidas'])->name('leer-todas');
        Route::post('/{notificacion}/leer',          [NotificacionController::class, 'marcarLeida'])     ->name('leer');
        Route::get('/conteo',                        [NotificacionController::class, 'conteo'])          ->name('conteo');
    });

    // Rutas exclusivas admin/gerente
    Route::middleware('rol:admin,gerente')->group(function () {
        Route::get('/dashboard/usuarios',  [DashboardController::class, 'usuarios']);
        Route::get('/dashboard/contenido', [DashboardController::class, 'contenido']);
    });

    // Rutas exclusivas admin
    // Rutas exclusivas admin
    Route::middleware('rol:admin')->prefix('dashboard/admin')->name('admin.')->group(function () {
 
        // Usuarios
        Route::get('/usuarios',                          [AdminController::class, 'usuarios'])->name('usuarios');
        Route::patch('/usuarios/{user}',                 [AdminController::class, 'updateUsuario'])->name('usuarios.update');
        Route::patch('/usuarios/{user}/toggle-bloqueo',  [AdminController::class, 'toggleBloqueo'])->name('usuarios.toggle');
 
        // Publicaciones
        Route::get('/publicaciones',                     [AdminController::class, 'publicaciones'])->name('publicaciones');
        Route::patch('/publicaciones/{producto}',        [AdminController::class, 'updatePublicacion'])->name('publicaciones.update');
        Route::delete('/publicaciones/{producto}',       [AdminController::class, 'destroyPublicacion'])->name('publicaciones.destroy');
    
        Route::get('/ingresos', [AdminController::class, 'ingresos'])->name('ingresos');
        });

