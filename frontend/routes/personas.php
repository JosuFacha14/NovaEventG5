<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonasController;



// Rutas — Módulo Personas (CRUD Completo)



// PA_PERSONAS
Route::prefix('personas')->name('personas.')->group(function () {
    Route::get('/',               [PersonasController::class, 'index'])         ->name('index');
    Route::post('/',              [PersonasController::class, 'store'])         ->name('store');
    Route::get('/{id}',           [PersonasController::class, 'show'])          ->name('show');
    Route::put('/{id}',           [PersonasController::class, 'update'])        ->name('update');
    Route::post('/{id}/telefonos',[PersonasController::class, 'storeTelefono']) ->name('telefonos.store');
    Route::post('/{id}/correos',  [PersonasController::class, 'storeCorreo'])   ->name('correos.store');
});

// PA_TIPO_USUARIOS
Route::prefix('tipos-usuario')->name('tipos-usuario.')->group(function () {
    Route::get('/',      [PersonasController::class, 'tiposUsuarioIndex']) ->name('index');
    Route::post('/',     [PersonasController::class, 'tiposUsuarioStore']) ->name('store');
    Route::put('/{id}',  [PersonasController::class, 'tiposUsuarioUpdate'])->name('update');
});

// PA_TIPO_CLIENTES
Route::prefix('tipos-cliente')->name('tipos-cliente.')->group(function () {
    Route::get('/',      [PersonasController::class, 'tiposClienteIndex']) ->name('index');
    Route::post('/',     [PersonasController::class, 'tiposClienteStore']) ->name('store');
    Route::put('/{id}',  [PersonasController::class, 'tiposClienteUpdate'])->name('update');
});

// USUARIOS
Route::prefix('usuarios')->name('usuarios.')->group(function () {
    Route::get('/',     [PersonasController::class, 'usuariosIndex']) ->name('index');
    Route::post('/',    [PersonasController::class, 'usuariosStore']) ->name('store');
    Route::put('/{id}', [PersonasController::class, 'usuariosUpdate'])->name('update');
});

// PA_CLIENTES
Route::prefix('clientes')->name('clientes.')->group(function () {
    Route::get('/',     [PersonasController::class, 'clientesIndex']) ->name('index');
    Route::post('/',    [PersonasController::class, 'clientesStore']) ->name('store');
    Route::put('/{id}', [PersonasController::class, 'clientesUpdate'])->name('update');
});

// PA_EMPLEADOS
Route::prefix('empleados')->name('empleados.')->group(function () {
    Route::get('/',     [PersonasController::class, 'empleadosIndex']) ->name('index');
    Route::post('/',    [PersonasController::class, 'empleadosStore']) ->name('store');
    Route::put('/{id}', [PersonasController::class, 'empleadosUpdate'])->name('update');
});

// PA_PROVEEDORES
Route::prefix('proveedores')->name('proveedores.')->group(function () {
    Route::get('/',     [PersonasController::class, 'proveedoresIndex']) ->name('index');
    Route::post('/',    [PersonasController::class, 'proveedoresStore']) ->name('store');
    Route::put('/{id}', [PersonasController::class, 'proveedoresUpdate'])->name('update');
});