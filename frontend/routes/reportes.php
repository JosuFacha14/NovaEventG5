<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportesController;

Route::prefix('mreportes')->group(function () {
    // 1. Panel General
    Route::get('/reportes', [ReportesController::class, 'index'])->name('mreportes.reportes.index');
    Route::get('/reportes-alt', [ReportesController::class, 'index'])->name('reportes.index');
    Route::post('/reportes/guardar', [ReportesController::class, 'storeReporte'])->name('reportes.store');
    Route::put('/reportes/actualizar/{id}', [ReportesController::class, 'updateReporte'])->name('reportes.update');

    // 2. Costos Operativos
    Route::get('/costos', [ReportesController::class, 'costosIndex'])->name('mreportes.costos.index');
    Route::get('/costos-alt', [ReportesController::class, 'costosIndex'])->name('reportes.costos');
    Route::post('/costos/guardar', [ReportesController::class, 'storeCosto'])->name('costos.store');
    Route::put('/costos/actualizar/{id}', [ReportesController::class, 'updateCosto'])->name('costos.update');

    // 3. Ganancias y Utilidades
    Route::get('/ganancias', [ReportesController::class, 'gananciasIndex'])->name('mreportes.ganancias.index');
    Route::get('/ganancias-alt', [ReportesController::class, 'gananciasIndex'])->name('reportes.ganancias');
    Route::post('/ganancias/guardar', [ReportesController::class, 'storeGanancia'])->name('ganancias.store');
    Route::put('/ganancias/actualizar/{id}', [ReportesController::class, 'updateGanancia'])->name('ganancias.update');

    // 4. Estado de Inventario
    Route::get('/inventario', [ReportesController::class, 'inventarioIndex'])->name('mreportes.inventario.index');
    Route::get('/inventario-alt', [ReportesController::class, 'inventarioIndex'])->name('reportes.inventario');
    Route::post('/inventario/guardar', [ReportesController::class, 'storeInventario'])->name('inventario.store');
    Route::put('/inventario/actualizar/{id}', [ReportesController::class, 'updateInventario'])->name('inventario.update');
});