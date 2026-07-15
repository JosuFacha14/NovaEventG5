<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VentasController;

/*
|--------------------------------------------------------------------------
| MÓDULO VENTAS
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CATEGORÍAS DE EVENTO
|--------------------------------------------------------------------------
*/

Route::get('/categorias-evento', [VentasController::class, 'categoriasIndex'])
    ->name('categorias-evento.index');

Route::post('/categorias-evento', [VentasController::class, 'categoriasStore'])
    ->name('categorias-evento.store');

Route::put('/categorias-evento/{id}', [VentasController::class, 'categoriasUpdate'])
    ->name('categorias-evento.update');

Route::put('/categorias-evento/eliminar/{id}', [VentasController::class, 'categoriasDestroy'])
    ->name('categorias-evento.destroy');


/*
|--------------------------------------------------------------------------
| CICLOS DE EVENTO
|--------------------------------------------------------------------------
*/

Route::get('/ciclos-evento', [VentasController::class, 'ciclosIndex'])
    ->name('ciclos-evento.index');

Route::post('/ciclos-evento', [VentasController::class, 'ciclosStore'])
    ->name('ciclos-evento.store');

Route::put('/ciclos-evento/{id}', [VentasController::class, 'ciclosUpdate'])
    ->name('ciclos-evento.update');

Route::put('/ciclos-evento/eliminar/{id}', [VentasController::class, 'ciclosDestroy'])
    ->name('ciclos-evento.destroy');


/*
|--------------------------------------------------------------------------
| GESTIÓN DE EVENTOS
|--------------------------------------------------------------------------
*/

Route::get('/eventos', [VentasController::class, 'eventosIndex'])
    ->name('eventos.index');

Route::post('/eventos', [VentasController::class, 'eventosStore'])
    ->name('eventos.store');

Route::put('/eventos/{id}', [VentasController::class, 'eventosUpdate'])
    ->name('eventos.update');

Route::put('/eventos/eliminar/{id}', [VentasController::class, 'eventosDestroy'])
    ->name('eventos.destroy');


/*
|--------------------------------------------------------------------------
| GESTIÓN DE BOLETOS
|--------------------------------------------------------------------------
*/

Route::get('/boletos', [VentasController::class, 'boletosIndex'])
    ->name('boletos.index');

Route::post('/boletos', [VentasController::class, 'boletosStore'])
    ->name('boletos.store');

Route::put('/boletos/{id}', [VentasController::class, 'boletosUpdate'])
    ->name('boletos.update');

Route::put('/boletos/eliminar/{id}', [VentasController::class, 'boletosDestroy'])
    ->name('boletos.destroy');


/*
|--------------------------------------------------------------------------
| GESTIÓN DE VENTAS
|--------------------------------------------------------------------------
*/

Route::get('/ventas', [VentasController::class, 'ventasIndex'])
    ->name('ventas.index');

Route::post('/ventas', [VentasController::class, 'ventasStore'])
    ->name('ventas.store');

Route::put('/ventas/{id}', [VentasController::class, 'ventasUpdate'])
    ->name('ventas.update');

Route::put('/ventas/eliminar/{id}', [VentasController::class, 'ventasDestroy'])
    ->name('ventas.destroy');

Route::get('/ventas/{id}/detalle', [VentasController::class, 'ventasShow'])
    ->name('ventas.show');


/*
|--------------------------------------------------------------------------
| DETALLE DE VENTAS
|--------------------------------------------------------------------------
*/

Route::get('/detalle-ventas', [VentasController::class, 'detalleVentasIndex'])
    ->name('detalle-ventas.index');

Route::post('/detalle-ventas', [VentasController::class, 'detalleVentasStore'])
    ->name('detalle-ventas.store');

Route::put('/detalle-ventas/{id}', [VentasController::class, 'detalleVentasUpdate'])
    ->name('detalle-ventas.update');

Route::put('/detalle-ventas/eliminar/{id}', [VentasController::class, 'detalleVentasDestroy'])
    ->name('detalle-ventas.destroy');


/*
|--------------------------------------------------------------------------
| PAGOS
|--------------------------------------------------------------------------
*/

Route::get('/pagos', [VentasController::class, 'pagosIndex'])
    ->name('pagos.index');

Route::post('/pagos', [VentasController::class, 'pagosStore'])
    ->name('pagos.store');

Route::put('/pagos/{id}', [VentasController::class, 'pagosUpdate'])
    ->name('pagos.update');

Route::put('/pagos/eliminar/{id}', [VentasController::class, 'pagosDestroy'])
    ->name('pagos.destroy');