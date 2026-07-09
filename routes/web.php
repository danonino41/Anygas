<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Recepcionista\AsignarController;
use App\Http\Controllers\Recepcionista\CatalogoController;
use App\Http\Controllers\Recepcionista\DashboardController;
use App\Http\Controllers\Recepcionista\HistorialController;
use App\Http\Controllers\Recepcionista\PosController;
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
        Route::get('/dashboard', fn() => view('administrador.dashboard'))->name('dashboard');
    });

    Route::prefix('recepcionista')->name('recepcionista.')->middleware('rol:recepcionista,administrador')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/punto-venta', [PosController::class, 'index'])->name('pos');
        Route::post('/punto-venta', [PosController::class, 'store'])->name('pos.guardar');
        Route::get('/buscar-cliente', [PosController::class, 'buscarCliente'])->name('buscar.cliente');
        
        Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo');
        Route::get('/asignar', [AsignarController::class, 'index'])->name('asignar');
        Route::post('/asignar/{pedido}', [AsignarController::class, 'store'])->name('asignar.procesar');
        Route::post('/asignar-multiple', [AsignarController::class, 'asignarMultiple'])->name('asignar.multiple');
        Route::get('/historial', [HistorialController::class, 'index'])->name('historial');
        Route::get('/ventas', [HistorialController::class, 'ventasDia'])->name('ventas');
    });

    Route::prefix('motorizado')->name('motorizado.')->middleware('rol:motorizado')->group(function () {
        Route::get('/dashboard', fn() => view('motorizado.dashboard'))->name('dashboard');
    });
});