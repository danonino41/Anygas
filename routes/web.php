<?php

use App\Http\Controllers\Administrador\DashboardController as AdminDashboardController;
use App\Http\Controllers\Administrador\KardexController;
use App\Http\Controllers\Administrador\KpisController;
use App\Http\Controllers\Administrador\ComprasController;
use App\Http\Controllers\Administrador\PersonalController;
use App\Http\Controllers\Administrador\ProductosController;
use App\Http\Controllers\Administrador\ClientesController;
use App\Http\Controllers\Administrador\SunatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Recepcionista\AsignarController;
use App\Http\Controllers\Recepcionista\CatalogoController;
use App\Http\Controllers\Recepcionista\DashboardController;
use App\Http\Controllers\Recepcionista\HistorialController;
use App\Http\Controllers\Recepcionista\PosController;
use App\Http\Controllers\Motorizado\RutaController;
use App\Http\Controllers\Motorizado\HistorialController as MotorizadoHistorialController;
use Illuminate\Support\Facades\Route;

// =================================================================
// 1. ZONA PÚBLICA
// =================================================================
Route::get('/', function () {
    return view('welcome');
})->name('comercial.inicio');

Route::get('/seguimiento', function () {
    return '<h1>🔍 Módulo de seguimiento en desarrollo</h1>';
})->name('cliente.seguimiento');

// =================================================================
// 2. AUTENTICACIÓN
// =================================================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.procesar');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =================================================================
// 3. ZONA PRIVADA (Protegida por Auth + Middleware de Rol)
// =================================================================

// Usaremos el middleware 'rol' que configuramos (ej: 'rol:administrador')
Route::middleware(['auth', 'rol:administrador,recepcionista,motorizado'])->group(function () {

    // --- MÓDULO ADMINISTRADOR ---
    Route::prefix('admin')->name('admin.')->middleware('rol:administrador')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/kpis', [KpisController::class, 'index'])->name('kpis');

        // Placeholder routes for future modules
        Route::get('/kardex', [KardexController::class, 'index'])->name('kardex');
        Route::get('/compras', [ComprasController::class, 'index'])->name('compras');
        Route::get('/compras/crear', [ComprasController::class, 'create'])->name('compras.crear');
        Route::post('/compras', [ComprasController::class, 'store'])->name('compras.guardar');
        Route::get('/personal', [PersonalController::class, 'index'])->name('personal');
        Route::post('/personal', [PersonalController::class, 'store'])->name('personal.guardar');
        Route::put('/personal/{id}', [PersonalController::class, 'update'])->name('personal.actualizar');
        Route::put('/personal/{id}/password', [PersonalController::class, 'updatePassword'])->name('personal.password');
        Route::put('/personal/{id}/estado', [PersonalController::class, 'toggleEstado'])->name('personal.estado');
        Route::get('/productos', [ProductosController::class, 'index'])->name('productos');
        Route::post('/productos', [ProductosController::class, 'store'])->name('productos.guardar');
        Route::put('/productos/{id}', [ProductosController::class, 'update'])->name('productos.actualizar');
        Route::put('/productos/{id}/precio', [ProductosController::class, 'updatePrice'])->name('productos.precio');
        Route::put('/productos/{id}/estado', [ProductosController::class, 'toggleEstado'])->name('productos.estado');
        Route::get('/clientes', [ClientesController::class, 'index'])->name('clientes');
        Route::post('/clientes', [ClientesController::class, 'store'])->name('clientes.guardar');
        Route::get('/clientes/{id}/ver', [ClientesController::class, 'ver'])->name('clientes.ver');
        Route::get('/clientes/{id}', [ClientesController::class, 'show'])->name('clientes.mostrar');
        Route::put('/clientes/{id}', [ClientesController::class, 'update'])->name('clientes.actualizar');
        Route::put('/clientes/{id}/estado', [ClientesController::class, 'toggleEstado'])->name('clientes.estado');
        Route::post('/clientes/{id}/telefonos', [ClientesController::class, 'addTelefono'])->name('clientes.telefono.agregar');
        Route::delete('/clientes/{id}/telefonos/{telefonoId}', [ClientesController::class, 'removeTelefono'])->name('clientes.telefono.eliminar');
        Route::post('/clientes/{id}/direcciones', [ClientesController::class, 'addDireccion'])->name('clientes.direccion.agregar');
        Route::delete('/clientes/{id}/direcciones/{direccionId}', [ClientesController::class, 'removeDireccion'])->name('clientes.direccion.eliminar');
        Route::get('/sunat', [SunatController::class, 'index'])->name('sunat');
        Route::get('/sunat/{id}', [SunatController::class, 'show'])->name('sunat.mostrar');
        Route::put('/sunat/{id}/estado', [SunatController::class, 'updateEstado'])->name('sunat.estado');
        Route::post('/sunat/{id}/enviar', [SunatController::class, 'enviar'])->name('sunat.enviar');
        Route::get('/sunat/{id}/pdf', [SunatController::class, 'pdf'])->name('sunat.pdf');
        Route::get('/sunat/{id}/ticket', [SunatController::class, 'ticket'])->name('sunat.ticket');
    });

    Route::prefix('recepcionista')->name('recepcionista.')->middleware('rol:recepcionista,administrador')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/punto-venta', [PosController::class, 'index'])->name('pos');
        Route::post('/punto-venta', [PosController::class, 'store'])->name('pos.guardar');
        Route::get('/buscar-cliente', [PosController::class, 'buscarCliente'])->name('buscar.cliente');
        Route::post('/consultar-documento', [PosController::class, 'consultarDocumento'])->name('consultar.documento');
        
        Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo');
        Route::get('/asignar', [AsignarController::class, 'index'])->name('asignar');
        Route::post('/asignar/{pedido}', [AsignarController::class, 'store'])->name('asignar.procesar');
        Route::post('/asignar-multiple', [AsignarController::class, 'asignarMultiple'])->name('asignar.multiple');
        Route::get('/historial', [HistorialController::class, 'index'])->name('historial');
        Route::get('/ventas', [HistorialController::class, 'ventasDia'])->name('ventas');
    });

    Route::prefix('motorizado')->name('motorizado.')->middleware('rol:motorizado')->group(function () {
        Route::get('/dashboard', [RutaController::class, 'dashboard'])->name('dashboard');
        Route::get('/ruta', [RutaController::class, 'index'])->name('ruta');
        Route::post('/ruta/{id}/iniciar', [RutaController::class, 'iniciarRuta'])->name('ruta.iniciar');
        Route::post('/ruta/{id}/notificar', [RutaController::class, 'notificarLlegada'])->name('ruta.notificar');
        Route::post('/ruta/{id}/entregar', [RutaController::class, 'confirmarEntrega'])->name('ruta.entregar');
        Route::get('/ruta/{id}/cobrar', [RutaController::class, 'cobrar'])->name('ruta.cobrar');
        Route::post('/ruta/{id}/cobrar', [RutaController::class, 'guardarCobro'])->name('ruta.cobrar.guardar');
        Route::get('/pendientes', [RutaController::class, 'pendientes'])->name('pendientes');
        Route::post('/pendientes/{pedido}/recoger', [RutaController::class, 'recogerEnvases'])->name('pendientes.recoger');
        Route::get('/historial', [MotorizadoHistorialController::class, 'index'])->name('historial');
    });
});