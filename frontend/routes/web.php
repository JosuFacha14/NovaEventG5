<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportesController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');

// Rutas del módulo Personas
require base_path('routes/personas.php');

// Rutas del modulo  de Reservaciones
require base_path('routes/reservacion.php');

// Rutas del módulo Ventas
require base_path('routes/ventas.php');

// Rutas del módulo Inventario
require base_path('routes/inventario.php');

// Rutas principales del módulo de Reportes
Route::prefix('mreportes')->group(function () {
    // 1. Panel General
    Route::get('/reportes', [ReportesController::class, 'index'])->name('mreportes.reportes.index');
    Route::get('/reportes', [ReportesController::class, 'index'])->name('reportes.index');

    // 2. Reporte de Costos
    Route::get('/costos', [ReportesController::class, 'costosIndex'])->name('mreportes.costos.index');
    Route::get('/costos', [ReportesController::class, 'costosIndex'])->name('reportes.costos');

    // 3. Reporte de Ganancias
    Route::get('/ganancias', [ReportesController::class, 'gananciasIndex'])->name('mreportes.ganancias.index');
    Route::get('/ganancias', [ReportesController::class, 'gananciasIndex'])->name('reportes.ganancias');

    // 4. Reporte de Inventario
    Route::get('/inventario', [ReportesController::class, 'inventarioIndex'])->name('mreportes.inventario.index');
    Route::get('/inventario', [ReportesController::class, 'inventarioIndex'])->name('reportes.inventario');
});