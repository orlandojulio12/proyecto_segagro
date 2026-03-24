<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CentroController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\User\UserController;

// Inventario
use App\Http\Controllers\Inventario\InventoriesGenController;
use App\Http\Controllers\Inventario\CatalogProductController;
use App\Http\Controllers\Inventario\SemovienteController;
use App\Http\Controllers\FerreteriaController;
use App\Http\Controllers\SalidaFerreteriaController;

// Infraestructura
use App\Http\Controllers\Infraestructura\InfraestructuraController;

// PQR
use App\Http\Controllers\Complaint\PqrController;

// Presupuesto
use App\Http\Controllers\Budget\BudgetController;

// Traslados
use App\Http\Controllers\Traslado\NeedTransferController;

// Contratos
use App\Http\Controllers\Contrato\ContractController;

// Config
use App\Http\Controllers\Dependency\DependencyController;
use App\Http\Controllers\Area\AreaController;
use App\Http\Controllers\Area\RoomController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| AUTH GROUP
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------
    | PROFILE
    |--------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------
    | USERS (solo SuperAdmin normalmente)
    |--------------------------------------------------
    */
    Route::middleware(['role:SuperAdministrador'])->prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------
    | CENTROS / SEDES
    |--------------------------------------------------
    */
    Route::resource('centros', CentroController::class)->middleware('permission:infraestructura.view');
    Route::resource('sedes', SedeController::class)->middleware('permission:infraestructura.view');

    Route::get('centros/{centro}/sedes', [FerreteriaController::class, 'getSedesByCentro']);

    /*
    |--------------------------------------------------
    | INVENTARIO
    |--------------------------------------------------
    */
    Route::prefix('inventario')->group(function () {

        Route::middleware('permission:inventario.view')->group(function () {
            Route::get('/catalogo', [CatalogProductController::class, 'index'])->name('catalogo.index');
            Route::get('/catalogo/data', [CatalogProductController::class, 'data'])->name('catalogo.data');
            Route::get('/catalogo/filters', [CatalogProductController::class, 'filters']) ->name('catalogo.filters');
        });
    });

    Route::prefix('inventoriesGen')->name('inventoriesGen.')->middleware('permission:inventario.view')->group(function () {
        Route::get('/', [InventoriesGenController::class, 'index'])->name('index');
    });

    /*
    | FERRETERIA
    */
    Route::prefix('ferreteria')->name('ferreteria.')->group(function () {

        Route::middleware('permission:inventario.view')->group(function () {
            Route::get('/', [FerreteriaController::class, 'index'])->name('index');
        });

        Route::middleware('permission:inventario.create')->group(function () {
            Route::get('/create', [FerreteriaController::class, 'create'])->name('create');
            Route::post('/', [FerreteriaController::class, 'store'])->name('store');
        });

        Route::middleware('permission:inventario.edit')->group(function () {
            Route::get('/{inventory}/edit', [FerreteriaController::class, 'edit'])->name('edit');
            Route::put('/{inventory}', [FerreteriaController::class, 'update'])->name('update');
        });

        Route::middleware('permission:inventario.delete')->group(function () {
            Route::delete('/{inventory}', [FerreteriaController::class, 'destroy'])->name('destroy');
        });
    });

    /*
    | SALIDA FERRETERIA
    */
    Route::prefix('salida-ferreteria')->name('salida_ferreteria.')->group(function () {

        Route::middleware('permission:inventario.view')->group(function () {
            Route::get('/', [SalidaFerreteriaController::class, 'index'])->name('index');
        });

        Route::middleware('permission:inventario.create')->group(function () {
            Route::get('/create', [SalidaFerreteriaController::class, 'create'])->name('create');
            Route::post('/', [SalidaFerreteriaController::class, 'store'])->name('store');
        });
    });

    /*
    | SEMOVIENTE
    */
    Route::prefix('semoviente')->name('semoviente.')->middleware('permission:semoviente.view')->group(function () {
        Route::get('/', [SemovienteController::class, 'index'])->name('index');
    });

    /*
    |--------------------------------------------------
    | INFRAESTRUCTURA
    |--------------------------------------------------
    */
    Route::prefix('infraestructura')->name('infraestructura.')->group(function () {

        Route::middleware('permission:infraestructura.view')->get('/', [InfraestructuraController::class, 'index'])->name('index');

        Route::middleware('permission:infraestructura.create')->group(function () {
            Route::get('/create', [InfraestructuraController::class, 'create'])->name('create');
            Route::post('/store', [InfraestructuraController::class, 'store'])->name('store');
        });

        Route::middleware('permission:infraestructura.edit')->group(function () {
            Route::get('/{infraestructura}/edit', [InfraestructuraController::class, 'edit'])->name('edit');
            Route::put('/{infraestructura}/update', [InfraestructuraController::class, 'update'])->name('update');
        });

        Route::middleware('permission:infraestructura.delete')->delete('/{id}', [InfraestructuraController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------
    | PRESUPUESTO
    |--------------------------------------------------
    */
    Route::prefix('budget')->name('budget.')->group(function () {

        Route::middleware('permission:presupuesto.view')->get('/', [BudgetController::class, 'index'])->name('index');

        Route::middleware('permission:presupuesto.create')->group(function () {
            Route::get('/create', [BudgetController::class, 'create'])->name('create');
            Route::post('/', [BudgetController::class, 'store'])->name('store');
        });

        Route::middleware('permission:presupuesto.edit')->group(function () {
            Route::get('/{budget}/edit', [BudgetController::class, 'edit'])->name('edit');
            Route::put('/{budget}', [BudgetController::class, 'update'])->name('update');
        });

        Route::middleware('permission:presupuesto.delete')->delete('/{budget}', [BudgetController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------
    | PQR
    |--------------------------------------------------
    */
    Route::prefix('pqr')->name('pqr.')->group(function () {

        Route::middleware('permission:pqr.view')->get('/listado', [PqrController::class, 'index'])->name('index');

        Route::middleware('permission:pqr.create')->group(function () {
            Route::get('/crear', [PqrController::class, 'create'])->name('create');
            Route::post('/store', [PqrController::class, 'store'])->name('store');
        });

        Route::middleware('permission:pqr.edit')->group(function () {
            Route::get('/{pqr}/editar', [PqrController::class, 'edit'])->name('edit');
            Route::put('/{pqr}/actualizar', [PqrController::class, 'update'])->name('update');
        });

        Route::middleware('permission:pqr.delete')->delete('/{pqr}', [PqrController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------
    | TRASLADOS
    |--------------------------------------------------
    */
    Route::prefix('traslados')->name('traslados.')->group(function () {

        Route::middleware('permission:traslados.view')->get('/', [NeedTransferController::class, 'index'])->name('index');

        Route::middleware('permission:traslados.create')->group(function () {
            Route::get('/crear', [NeedTransferController::class, 'create'])->name('create');
            Route::post('/guardar', [NeedTransferController::class, 'store'])->name('store');
        });

        Route::middleware('permission:traslados.edit')->group(function () {
            Route::get('/{id}/editar', [NeedTransferController::class, 'edit'])->name('edit');
            Route::put('/{id}', [NeedTransferController::class, 'update'])->name('update');
        });

        Route::middleware('permission:traslados.delete')->delete('/{id}', [NeedTransferController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------
    | CONTRATOS
    |--------------------------------------------------
    */
    Route::prefix('contracts')->name('contracts.')->group(function () {

        Route::middleware('permission:contratos.view')->get('/', [ContractController::class, 'index'])->name('index');

        Route::middleware('permission:contratos.create')->group(function () {
            Route::get('/create', [ContractController::class, 'create'])->name('create');
            Route::post('/', [ContractController::class, 'store'])->name('store');
        });

        Route::middleware('permission:contratos.edit')->group(function () {
            Route::get('/{contract}/edit', [ContractController::class, 'edit'])->name('edit');
            Route::put('/{contract}', [ContractController::class, 'update'])->name('update');
        });

        Route::middleware('permission:contratos.delete')->delete('/{contract}', [ContractController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------
    | CONFIGURACIÓN
    |--------------------------------------------------
    */
    Route::prefix('dependencies')->name('dependencies.')->middleware('permission:infraestructura.view')->group(function () {
        Route::get('/', [DependencyController::class, 'index'])->name('index');
        Route::get('/create', [DependencyController::class, 'create'])->name('create');
        Route::post('/', [DependencyController::class, 'store'])->name('store');
        Route::get('/{dependency}', [DependencyController::class, 'show'])->name('show');
        Route::get('/{dependency}/edit', [DependencyController::class, 'edit'])->name('edit');
        Route::put('/{dependency}', [DependencyController::class, 'update'])->name('update');
        Route::delete('/{dependency}', [DependencyController::class, 'destroy'])->name('destroy');
        Route::post('/subunits/reorder', [DependencyController::class, 'reorder']); 
        // Subdependencias 
        Route::post('/{dependency}/subunit', [DependencyController::class, 'storeSubunit']) ->name('subunit.store'); 
        Route::delete('/subunit/{subunit}', [DependencyController::class, 'destroySubunit']) ->name('subunit.destroy');
    });

    Route::prefix('areas')->name('areas.')->middleware('permission:infraestructura.view')->group(function () {
        Route::get('/', [AreaController::class, 'index'])->name('index');
        Route::get('/create', [AreaController::class, 'create'])->name('create');
        Route::post('/store', [AreaController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AreaController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [AreaController::class, 'update'])->name('update');
    });

    Route::prefix('rooms')->name('rooms.')->middleware('permission:infraestructura.view')->group(function () {
        Route::get('/', [RoomController::class, 'index'])->name('index');
        Route::get('/create', [RoomController::class, 'create'])->name('create');
        Route::post('/store', [RoomController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [RoomController::class, 'edit'])->name('edit');
        Route::get('/filter', [RoomController::class, 'filter'])->name('filter');
        Route::put('/{id}/update', [RoomController::class, 'update'])->name('update');
    });

    Route::get('/sedes/{sede}/areas', [AreaController::class, 'getBySede'])->name('areas.bySede'); // AJAX Route::get('/{areaId}/rooms', [RoomController::class, 'getRoomsByArea']) ->name('rooms.byArea'); });

    Route::get('/centros/{centro}/sedes-areas', [SedeController::class, 'ajaxSedesAreas']);
    Route::get('/centros/{centro}/sedes-centro', [SedeController::class, 'ajaxSedesCentro']);
});

require __DIR__ . '/auth.php';
