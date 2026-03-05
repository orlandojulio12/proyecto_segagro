<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Inventario\CatalogProduct;
use App\Models\InventorySede;

class InventoriesGenController extends Controller
{
    public function index()
    {

        $inventarios = InventorySede::with([
            'sede.centro',
            'staff',
            'materials',
            'semovientes.staff'
        ])->get();

        $totalInventarios = $inventarios->count();

        $totalMateriales = $inventarios->sum(function ($inv) {
            return $inv->materials->count();
        });

        $totalSemovientes = $inventarios->sum(function ($inv) {
            return $inv->semovientes->count();
        });

        $totalCatalogo = CatalogProduct::count();

        /* crecimiento simple */
        $recentInventarios = InventorySede::whereMonth('record_date', now()->month)->count();
        $previousInventarios = InventorySede::whereMonth('record_date', now()->subMonth()->month)->count();

        $growth = $previousInventarios > 0
            ? (($recentInventarios - $previousInventarios) / $previousInventarios) * 100
            : 0;

        return view('ferreteria.general.general_index', compact(
            'inventarios',
            'totalInventarios',
            'totalMateriales',
            'totalSemovientes',
            'totalCatalogo',
            'growth'
        ));
    }
}
