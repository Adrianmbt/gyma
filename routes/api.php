<?php

use App\Http\Controllers\EntrenadorController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\MiembroController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SuscripcionController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VentaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('usuarios', UsuarioController::class);
Route::apiResource('miembros', MiembroController::class);
Route::get('miembros/buscar/{cedula}', [MiembroController::class, 'buscarPorCedula']);
Route::apiResource('entrenadores', EntrenadorController::class);
Route::apiResource('planes', PlanController::class);
Route::apiResource('inventario', InventarioController::class);
Route::apiResource('suscripciones', SuscripcionController::class);
Route::apiResource('registros', \App\Http\Controllers\RegistroController::class)->only(['index']);

// Rutas para Ventas
Route::get('productos-venta', [VentaController::class, 'getProductosParaVenta']);
Route::apiResource('ventas', VentaController::class)->only(['index', 'store', 'show']);
Route::apiResource('usuarios', UsuarioController::class)->middleware('can:viewAdminContent');

// Endpoint para estadísticas del dashboard
Route::get('/reportes/dashboard-stats', [ReporteController::class, 'getDashboardStats'])
    ->middleware('can:viewAdminContent');

Route::get('/bcv', [App\Http\Controllers\BcvApiController::class, 'getTasa']);
