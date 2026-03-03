<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\InventorySede;
use App\Models\Dependency\DependencyUnit;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class InventoriesGenController extends Controller
{
    public function index()
    {
        $dependencies = DependencyUnit::orderBy('short_name')->get();
        return view('ferreteria.general.general_index', compact('dependencies'));
    }

    public function indexAjax(Request $request)
    {
        $query = InventorySede::with([
            'sede.centro',
            'staff',
            'materials',
            'semovientes.staff',
        ]);

        // Filtrar por dependencia si se envía
        if ($request->filled('dependency_unit_id')) {
            $query->where('responsible_department', $request->dependency_unit_id);
        }

        return DataTables::of($query)
            ->addColumn('sede', fn($row) => $row->sede->nom_sede ?? 'N/A')
            ->addColumn('centro', fn($row) => $row->sede->centro->nom_centro ?? 'N/A')
            ->addColumn('staff', fn($row) => $row->staff->name ?? 'N/A')
            ->addColumn('tipo', function ($row) {
                $badges = '';
                if ($row->materials->count()) $badges .= '<span class="badge bg-success"><i class="fas fa-box me-1"></i>Material</span> ';
                if ($row->semovientes->count()) $badges .= '<span class="badge bg-info"><i class="fas fa-paw me-1"></i>Semoviente</span>';
                return $badges;
            })
            ->addColumn('detalle', function ($row) {
                $detalle = '';
                foreach ($row->materials as $m) {
                    $detalle .= "<div><strong>{$m->material_name}</strong>";
                    if ($m->material_brand) $detalle .= " | Marca: {$m->material_brand}";
                    if ($m->material_model) $detalle .= " | Modelo: {$m->material_model}";
                    if ($m->material_serial) $detalle .= " | Serie: {$m->material_serial}";
                    $detalle .= "</div>";
                }
                foreach ($row->semovientes as $a) {
                    $detalle .= "<div>Tipo: {$a->animal_type} | Raza: " . ($a->breed ?? 'N/A') . " | Género: {$a->gender}</div>";
                }
                return $detalle;
            })
            ->addColumn('record_date', fn($row) => $row->record_date?->format('d/m/Y') ?? 'N/A')
            ->addColumn('cantidad', function ($row) {
                $materials = $row->materials->sum('material_quantity');
                $animals = $row->semovientes->count();
                $badges = '';
                if ($materials) $badges .= "<span class='badge bg-success'>{$materials} Materiales</span> ";
                if ($animals) $badges .= "<span class='badge bg-info'>{$animals} Semovientes</span>";
                return $badges;
            })
            ->addColumn('valor', function ($row) {
                $total = $row->materials->sum('total_with_tax') + $row->semovientes->sum('approx_value');
                return '$' . number_format($total, 2);
            })
            ->addColumn('acciones', function ($row) {
                return "<a href='" . route('inventoriesGen.index', $row) . "' class='btn btn-sm btn-warning'><i class='fas fa-edit'></i></a>";
            })
            ->rawColumns(['tipo', 'detalle', 'cantidad', 'acciones'])
            ->make(true);
    }
}
