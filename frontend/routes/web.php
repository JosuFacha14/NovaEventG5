<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');

// Rutas del módulo Personas
require base_path('routes/personas.php');

// Rutas del módulo de Reservaciones
require base_path('routes/reservacion.php');

// Rutas del módulo Ventas
require base_path('routes/ventas.php');

// Rutas del módulo Inventario
require base_path('routes/inventario.php');

// Rutas del módulo Reportes
require base_path('routes/reportes.php');