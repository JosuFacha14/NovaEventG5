<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportesController;

Route::prefix('mreportes')->group(function () {
    Route::get('/reportes', [ReportesController::class, 'index'])->name('mreportes.reportes.index');
    Route::get('/costos', [ReportesController::class, 'costosIndex'])->name('mreportes.costos.index');
    Route::get('/ganancias', [ReportesController::class, 'gananciasIndex'])->name('mreportes.ganancias.index');
    Route::get('/inventario', [ReportesController::class, 'inventarioIndex'])->name('mreportes.inventario.index');
});