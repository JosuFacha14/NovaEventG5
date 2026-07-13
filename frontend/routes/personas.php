<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonasController;

/*
|--------------------------------------------------------------------------
| Rutas — Módulo Personas
| Incluir desde routes/web.php con:
|   require base_path('routes/personas.php');
|--------------------------------------------------------------------------
*/

// -----------------------------------------------------------------------
// PA_PERSONAS
// -----------------------------------------------------------------------
Route::prefix('personas')->name('personas.')->group(function () {

    Route::get('/',        [PersonasController::class, 'index'])  ->name('index');
    Route::post('/',       [PersonasController::class, 'store'])  ->name('store');
    Route::get('/{id}',    [PersonasController::class, 'show'])   ->name('show');
    Route::put('/{id}',    [PersonasController::class, 'update']) ->name('update');
    Route::post('/{id}/telefonos', [PersonasController::class, 'storeTelefono']) ->name('telefonos.store');
    Route::post('/{id}/correos',   [PersonasController::class, 'storeCorreo'])   ->name('correos.store');
});

// -----------------------------------------------------------------------
// PA_TIPO_USUARIOS
// -----------------------------------------------------------------------
Route::prefix('tipos-usuario')->name('tipos-usuario.')->group(function () {

    Route::get('/',  [PersonasController::class, 'tiposUsuarioIndex']) ->name('index');
    Route::post('/', [PersonasController::class, 'tiposUsuarioStore']) ->name('store');

});

// -----------------------------------------------------------------------
// PA_TIPO_CLIENTES
// -----------------------------------------------------------------------
Route::prefix('tipos-cliente')->name('tipos-cliente.')->group(function () {

    Route::get('/',  [PersonasController::class, 'tiposClienteIndex']) ->name('index');
    Route::post('/', [PersonasController::class, 'tiposClienteStore']) ->name('store');
    
});