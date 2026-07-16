<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventarioController;

// RUTAS DEL MÓDULO DE INVENTARIO

// IN_CATEGORIAS_INVENTARIO
Route::prefix('inventario/categorias')->name('inventario.categorias.')->group(function () {
    Route::get('/',          [InventarioController::class, 'categoriasIndex'])->name('index');
    Route::post('/',         [InventarioController::class, 'categoriasStore'])->name('store');
    Route::put('/{id}',      [InventarioController::class, 'categoriasUpdate'])->name('update');
    Route::put('/{id}/baja', [InventarioController::class, 'categoriasBaja'])->name('baja');
});

// IN_ALMACENES
Route::prefix('inventario/almacenes')->name('inventario.almacenes.')->group(function () {
    Route::get('/',          [InventarioController::class, 'almacenesIndex'])->name('index');
    Route::post('/',         [InventarioController::class, 'almacenesStore'])->name('store');
    Route::put('/{id}',      [InventarioController::class, 'almacenesUpdate'])->name('update');
    Route::put('/{id}/baja', [InventarioController::class, 'almacenesBaja'])->name('baja');
});

// IN_INVENTARIO_ITEM
Route::prefix('inventario/items')->name('inventario.items.')->group(function () {
    Route::get('/',          [InventarioController::class, 'itemsIndex'])->name('index');
    Route::post('/',         [InventarioController::class, 'itemsStore'])->name('store');
    Route::put('/{id}',      [InventarioController::class, 'itemsUpdate'])->name('update');
    Route::put('/{id}/baja', [InventarioController::class, 'itemsBaja'])->name('baja');
});

// IN_RESERVAS_INVENTARIO
Route::prefix('inventario/reservas')->name('inventario.reservas.')->group(function () {
    Route::get('/',     [InventarioController::class, 'reservasIndex'])->name('index');
    Route::post('/',    [InventarioController::class, 'reservasStore'])->name('store');
    Route::put('/{id}', [InventarioController::class, 'reservasUpdate'])->name('update');
});

// IN_ASIGNACION_EVENTO
Route::prefix('inventario/asignaciones')->name('inventario.asignaciones.')->group(function () {
    Route::get('/',     [InventarioController::class, 'asignacionesIndex'])->name('index');
    Route::post('/',    [InventarioController::class, 'asignacionesStore'])->name('store');
    Route::put('/{id}', [InventarioController::class, 'asignacionesUpdate'])->name('update');
});
