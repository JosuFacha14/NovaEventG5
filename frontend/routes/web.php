<?php

use Illuminate\Support\Facades\Route;

require base_path('routes/auth.php');

Route::middleware(['auth.sesion'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');

    // Rutas del modulo Personas
    require base_path('routes/personas.php');

    // Rutas del modulo de Reservaciones
    require base_path('routes/reservacion.php');

    // Rutas del modulo Ventas
    require base_path('routes/ventas.php');

    // Rutas del modulo Inventario
    require base_path('routes/inventario.php');

    // Rutas del modulo Reportes
    require base_path('routes/reportes.php');
});