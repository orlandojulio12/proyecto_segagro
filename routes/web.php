<?php

use App\Http\Controllers\CentroController;
use App\Http\Controllers\FerreteriaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\SalidaFerreteriaController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\PresupuestoController;
use App\Http\Controllers\Infraestructura\InfraestructuraController;
use App\Http\Controllers\Inventario\SemovienteController;
use App\Http\Controllers\QuejaController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\Traslado\NeedTransferController;
use App\Http\Controllers\TrasladoController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Contrato\ContractController;
use App\Http\Controllers\Inventario\CatalogProductController;

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


    //Modulo Ferretería

    Route::prefix('ferreteria')->name('ferreteria.')->group(function () {
        // 1️⃣ PRIMERO: Rutas estáticas (sin parámetros)
        Route::get('/', [FerreteriaController::class, 'index'])->name('index');
        Route::get('/create', [FerreteriaController::class, 'create'])->name('create');

        // ✅ PLANTILLA DEBE IR ANTES DE LAS RUTAS DINÁMICAS
        Route::get('/plantilla/descargar', [FerreteriaController::class, 'downloadTemplate'])
            ->name('template.download');

        // ✅ IMPORT TAMBIÉN ES ESTÁTICA
        Route::post('/import-materials', [FerreteriaController::class, 'importMaterials'])
            ->name('import.materials');

        // 2️⃣ DESPUÉS: Rutas dinámicas (con {parámetros})
        Route::post('/', [FerreteriaController::class, 'store'])->name('store');
        Route::get('/{inventory}', [FerreteriaController::class, 'show'])->name('show');
        Route::get('/{inventory}/edit', [FerreteriaController::class, 'edit'])->name('edit');
        Route::put('/{inventory}', [FerreteriaController::class, 'update'])->name('update');
        Route::delete('/{inventory}', [FerreteriaController::class, 'destroy'])->name('destroy');
    });

    // Catalogo de productos
    Route::get('/catalogo', [CatalogProductController::class, 'index'])
        ->name('catalogo.index');

    Route::get('/catalogo/data', [CatalogProductController::class, 'data'])
        ->name('catalogo.data');


    Route::prefix('salida-ferreteria')->name('salida_ferreteria.')->group(function () {
        Route::get('/', [SalidaFerreteriaController::class, 'index'])->name('index');
        Route::get('/create', [SalidaFerreteriaController::class, 'create'])->name('create');
        Route::post('/', [SalidaFerreteriaController::class, 'store'])->name('store');
        Route::get('/{salidaFerreteria}', [SalidaFerreteriaController::class, 'show'])->name('show');
        Route::get('/{salidaFerreteria}/edit', [SalidaFerreteriaController::class, 'edit'])->name('edit');
        Route::put('/{salidaFerreteria}', [SalidaFerreteriaController::class, 'update'])->name('update');
        Route::delete('/{salidaFerreteria}', [SalidaFerreteriaController::class, 'destroy'])->name('destroy');

        // Ruta AJAX para obtener materiales por sede
        Route::get('/materiales/sede/{sedeId}', [SalidaFerreteriaController::class, 'getMaterialesBySede'])->name('materiales.sede');
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

    Route::prefix('infraestructura')->name('infraestructura.')->group(function () {
        Route::get('/', [InfraestructuraController::class, 'index'])->name('index');
        Route::get('/create', [InfraestructuraController::class, 'create'])->name('create');
        Route::post('/store', [InfraestructuraController::class, 'store'])->name('store');
        Route::get('/{infraestructura}/edit', [InfraestructuraController::class, 'edit'])->name('edit');
        Route::put('/{infraestructura}/update', [InfraestructuraController::class, 'update'])->name('update');

        Route::delete('/{id}', [InfraestructuraController::class, 'destroy'])->name('destroy');
    });

    // Route::resource('calendario', CalendarioController::class);
    // Route::resource('infraestructura', InfraestructuraController::class);
    // Route::resource('presupuesto', PresupuestoController::class);
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

    Route::prefix('contracts')->name('contracts.')->group(function () {
        // Listado
        Route::get('/', [ContractController::class, 'index'])->name('index');

        // Crear
        Route::get('/create', [ContractController::class, 'create'])->name('create');
        Route::post('/', [ContractController::class, 'store'])->name('store');

        // Ver detalle
        Route::get('/{contract}', [ContractController::class, 'show'])->name('show');

        // Editar
        Route::get('/{contract}/edit', [ContractController::class, 'edit'])->name('edit');
        Route::put('/{contract}', [ContractController::class, 'update'])->name('update');

        // Eliminar
        Route::delete('/{contract}', [ContractController::class, 'destroy'])->name('destroy');

        // Rutas AJAX
        Route::get('/sedes/centro/{centroId}', [ContractController::class, 'getSedesByCentro'])->name('sedes.centro');
        Route::get('/types/dependencia/{dependenciaId}', [ContractController::class, 'getTypesByDependencia'])->name('types.dependencia');

        // Estadísticas y reportes
        Route::get('/api/statistics', [ContractController::class, 'statistics'])->name('statistics');
        Route::get('/report/generate', [ContractController::class, 'report'])->name('report');
    });
});

require __DIR__ . '/auth.php';
