<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');
//ruta de personas
require base_path('routes/personas.php');