<?php

use App\Http\Controllers\CentroController;
use App\Http\Controllers\FerreteriaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\SedeController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\ContabilidadController;
use App\Http\Controllers\Infraestructura\InfraestructuraController;
use App\Http\Controllers\Inventario\SemovienteController;
use App\Http\Controllers\QuejaController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\Traslado\NeedTransferController;
use App\Http\Controllers\TrasladoController;
use App\Http\Controllers\User\UserController;
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

    Route::prefix('ferreteria')->name('ferreteria.')->group(function () {
        Route::get('/', [FerreteriaController::class, 'index'])->name('index');
        Route::get('/create', [FerreteriaController::class, 'create'])->name('create');
        Route::post('/', [FerreteriaController::class, 'store'])->name('store');
        Route::get('/{inventory}', [FerreteriaController::class, 'show'])->name('show');
        Route::get('/{inventory}/edit', [FerreteriaController::class, 'edit'])->name('edit');
        Route::put('/{inventory}', [FerreteriaController::class, 'update'])->name('update');
        Route::delete('/{inventory}', [FerreteriaController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('semoviente')->name('semoviente.')->group(function () {
        Route::get('/', [SemovienteController::class, 'index'])->name('index');           // Listado
        Route::get('/create', [SemovienteController::class, 'create'])->name('create');   // Formulario creación
        Route::post('/store', [SemovienteController::class, 'store'])->name('store');     // Guardar nuevo

        Route::get('/{semoviente}', [SemovienteController::class, 'show'])->name('show'); // Detalle
        Route::get('/{semoviente}/edit', [SemovienteController::class, 'edit'])->name('edit'); // Formulario edición
        Route::put('/{semoviente}', [SemovienteController::class, 'update'])->name('update'); // Actualizar
        Route::delete('/{semoviente}', [SemovienteController::class, 'destroy'])->name('destroy'); // Eliminar
    });

    Route::prefix('traslados')->name('traslados.')->group(function () {
        Route::get('/', [NeedTransferController::class, 'index'])->name('index');
        Route::get('/crear', [NeedTransferController::class, 'create'])->name('create');
        Route::post('/guardar', [NeedTransferController::class, 'store'])->name('store');
        Route::get('/{id}/editar', [NeedTransferController::class, 'edit'])->name('edit');
        Route::put('/{id}', [NeedTransferController::class, 'update'])->name('update');
        Route::delete('/{id}', [NeedTransferController::class, 'destroy'])->name('destroy');
    });

    Route::get('centros/{centro}/sedes', [FerreteriaController::class, 'getSedesByCentro']);

    Route::resource('infraestructura', InfraestructuraController::class);

    // Route::resource('calendario', CalendarioController::class);
    // Route::resource('infraestructura', InfraestructuraController::class);
    // Route::resource('contabilidad', ContabilidadController::class);
    // Route::resource('quejas', QuejaController::class);
    // Route::resource('planes', PlanController::class);
    // Route::resource('traslados', TrasladoController::class);

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit'); // ✅ NUEVA RUTA
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__ . '/auth.php';
