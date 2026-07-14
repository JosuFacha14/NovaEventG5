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