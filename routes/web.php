<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MiembroController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SuscripcionController;
use App\Http\Controllers\EntrenadorController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ReporteController;

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/api/auth/login', [AuthController::class, 'login']);
Route::post('/api/auth/logout', [AuthController::class, 'logout']);

// Rutas protegidas
Route::middleware(['auth.session'])->group(function () {
    Route::get('/', function () {
        return view('index');
    })->name('home');

    // API Miembros
    Route::prefix('api/miembros')->group(function () {
        Route::post('/listar', [MiembroController::class, 'listar']);
        Route::post('/store', [MiembroController::class, 'store']);
        Route::get('/show/{id}', [MiembroController::class, 'show']);
        Route::get('/cedula', [MiembroController::class, 'getByCedula']);
        Route::post('/update-status', [MiembroController::class, 'updateStatus']);
        Route::post('/vetar/{id}', [MiembroController::class, 'vetar']);
        Route::post('/reactivar/{id}', [MiembroController::class, 'reactivar']);
        Route::delete('/destroy/{id}', [MiembroController::class, 'destroy']);
    });

    // API Planes
    Route::prefix('api/planes')->group(function () {
        Route::get('/listar', [PlanController::class, 'listar']);
        Route::post('/store', [PlanController::class, 'store']);
        Route::get('/show/{id}', [PlanController::class, 'show']);
        Route::delete('/destroy/{id}', [PlanController::class, 'destroy']);
    });

    // API Suscripciones
    Route::prefix('api/suscripciones')->group(function () {
        Route::post('/store', [SuscripcionController::class, 'store']);
        Route::get('/registros-recientes', [SuscripcionController::class, 'registrosRecientes']);
    });

    // API Entrenadores
    Route::prefix('api/entrenadores')->group(function () {
        Route::get('/listar', [EntrenadorController::class, 'listar']);
        Route::post('/store', [EntrenadorController::class, 'store']);
        Route::get('/show/{id}', [EntrenadorController::class, 'show']);
        Route::delete('/destroy/{id}', [EntrenadorController::class, 'destroy']);
    });

    // API Inventario
    Route::prefix('api/inventario')->group(function () {
        Route::get('/listar', [InventarioController::class, 'listar']);
        Route::post('/store', [InventarioController::class, 'store']);
        Route::get('/show/{id}', [InventarioController::class, 'show']);
        Route::delete('/destroy/{id}', [InventarioController::class, 'destroy']);
    });

    // API Ventas
    Route::prefix('api/ventas')->group(function () {
        Route::post('/store', [VentaController::class, 'store']);
        Route::get('/del-dia', [VentaController::class, 'ventasDelDia']);
    });

    // API Usuarios
    Route::prefix('api/usuarios')->group(function () {
        Route::get('/listar', [UsuarioController::class, 'listar']);
        Route::post('/store', [UsuarioController::class, 'store']);
        Route::get('/show/{id}', [UsuarioController::class, 'show']);
        Route::delete('/destroy/{id}', [UsuarioController::class, 'destroy']);
    });

    // API Reportes
    Route::prefix('api/reportes')->group(function () {
        Route::get('/dashboard-dia', [ReporteController::class, 'dashboardDia']);
        Route::get('/reporte-diario', [ReporteController::class, 'reporteDiario']);
        Route::get('/tendencia-semanal', [ReporteController::class, 'tendenciaSemanal']);
        Route::get('/transacciones-dia', [ReporteController::class, 'transaccionesDia']);
        Route::get('/pdf-dia', [ReporteController::class, 'generarPdfDia']);
    });

    // API BCV
    Route::get('/api/bcv/tasa', [\App\Http\Controllers\BcvController::class, 'obtenerTasa']);
    Route::post('/api/bcv/limpiar-cache', [\App\Http\Controllers\BcvController::class, 'limpiarCache']);
});
