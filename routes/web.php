<?php

use App\Http\Controllers\CentroController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;


use App\Http\Controllers\UserController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\InfraestructuraController;
use App\Http\Controllers\ContabilidadController;
use App\Http\Controllers\QuejaController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\TrasladoController;
use Illuminate\Support\Facades\Route;

// Redirigir raíz a login
Route::get('/', function () {
    return redirect('/login');
});

// Dashboard con controlador
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Módulos SEGAGRO
    Route::resource('centros', CentroController::class);
    // Route::resource('usuarios', UserController::class);
    Route::resource('sedes', SedeController::class);
    Route::resource('inventories', InventoryController::class);
    Route::get('centros/{centro}/sedes', [InventoryController::class, 'getSedesByCentro']);
    // Route::resource('calendario', CalendarioController::class);
    // Route::resource('infraestructura', InfraestructuraController::class);
    // Route::resource('contabilidad', ContabilidadController::class);
    // Route::resource('quejas', QuejaController::class);
    // Route::resource('planes', PlanController::class);
    // Route::resource('traslados', TrasladoController::class);
});

require __DIR__.'/auth.php';