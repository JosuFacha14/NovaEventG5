<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservacionController;

// RUTAS DEL MODULO DE RESERVACION

// RE_ESPACIO
Route::prefix('espacios')->name('espacios.')->group(function () {
    Route::get('/',      [ReservacionController::class, 'espaciosIndex'])->name('index');
    Route::post('/',     [ReservacionController::class, 'espaciosStore'])->name('store');
    Route::put('/{id}',  [ReservacionController::class, 'espaciosUpdate'])->name('update');
});

// RE_RESERVACION
Route::prefix('reservaciones')->name('reservaciones.')->group(function () {
    Route::get('/',      [ReservacionController::class, 'reservacionesIndex'])->name('index');
    Route::post('/',     [ReservacionController::class, 'reservacionesStore'])->name('store');
    Route::put('/{id}',  [ReservacionController::class, 'reservacionesUpdate'])->name('update');
});

// RE_ESPACIO_OCUPADO
Route::prefix('espacios-ocupados')->name('espacios_ocupados.')->group(function () {
    Route::get('/',      [ReservacionController::class, 'espaciosOcupadosIndex'])->name('index');
    Route::post('/',     [ReservacionController::class, 'espaciosOcupadosStore'])->name('store');
    Route::put('/{id}',  [ReservacionController::class, 'espaciosOcupadosUpdate'])->name('update');
});

// RE_HISTORIAL_RESERVACION
Route::prefix('historial-reservaciones')->name('historial_reservaciones.')->group(function () {
    Route::get('/',      [ReservacionController::class, 'historialIndex'])->name('index');
});
