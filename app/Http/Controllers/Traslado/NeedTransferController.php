<?php

namespace App\Http\Controllers\Traslado;

use App\Http\Controllers\Controller;
use App\Models\Centro;
use App\Models\Dependency\DependencyUnit;
use App\Models\InventoryMaterial;
use App\Models\InventorySede;
use App\Models\Sede;
use App\Models\Traslado\NeedTransfer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NeedTransferController extends Controller
{
    public function index()
    {
        // traer lo necesario y nombrarlo como $traslados
        $traslados = NeedTransfer::with(['user', 'centroInicial', 'sedeInicial', 'centroFinal', 'sedeFinal', 'materiales'])->get();
        return view('traslados.index', compact('traslados'));
    }

    public function create()
    {
        $users = User::all();
        $usuarios = User::all();
        $materials = InventoryMaterial::all();
        $centros = Centro::all();
        $sedes = Sede::all();
        $units = DependencyUnit::with('subunits')->get();
        return view('traslados.create', compact('users', 'centros', 'sedes', 'units', 'materials'));
    }

    public function buscarMateriales(Request $request)
    {
        $search = $request->get('search', '');
        $materials = InventoryMaterial::with('inventory.sede')
            ->when($search, function ($query, $search) {
                $query->where('material_name', 'like', "%{$search}%")
                    ->orWhere('material_type', 'like', "%{$search}%");
            })
            ->paginate(10);

        return response()->json($materials);
    }

    public function store(Request $request)
    {
        $request->merge([
            'centro_inicial_id' => $request->input('inicial_centro_id'),
            'sede_inicial_id'   => $request->input('inicial_sede_id'),
            'centro_final_id'   => $request->input('final_centro_id'),
            'sede_final_id'     => $request->input('final_sede_id'),
            'unidad_id'         => $request->input('unidad_id'),      // <-- nuevo
            'subunidad_id'      => $request->input('subunidad_id'),   // <-- nuevo
        ]);

        $data = $request->validate([
            'unidad_id' => 'required|exists:dependency_units,dependency_unit_id',
            'subunidad_id' => 'required|exists:dependency_subunits,subunit_id',
            'centro_inicial_id' => 'nullable|exists:centros,id',
            'sede_inicial_id' => 'nullable|exists:sedes,id',
            'centro_final_id' => 'nullable|exists:centros,id',
            'sede_final_id' => 'nullable|exists:sedes,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'descripcion' => 'nullable|string',
            'nivel_riesgo' => 'required|string',
            'nivel_complejidad' => 'required|string',
            'presupuesto_solicitado' => 'nullable|numeric',
            'presupuesto_aceptado' => 'nullable|numeric',
            'requiere_personal' => 'boolean',
            'requiere_materiales' => 'boolean',
        ]);
        $data['user_id'] = Auth::id();
        $data['status'] = 'pendiente'; // <-- status inicial
        //dd($data);
        $traslado = NeedTransfer::create($data);

        if ($request->filled('personal')) {
            $traslado->personal()->sync($request->personal);
        }

        if ($request->filled('materiales')) {
            $syncData = [];
            foreach ($request->materiales as $mat) {
                $syncData[$mat['id']] = [
                    'cantidad' => $mat['cantidad'],
                    'tipo' => $mat['tipo'] ?? null,
                ];
            }
            $traslado->materiales()->sync($syncData);
        }
        // <-- redirige a la ruta con prefijo 'traslados'
        return redirect()->route('traslados.index')->with('success', 'Necesidad de traslado creada correctamente');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $traslado = NeedTransfer::with(['personal', 'materiales'])->findOrFail($id);

        $users = User::all();
        $centros = Centro::all();
        $sedes = Sede::all();
        $units = DependencyUnit::with('subunits')->get();
        $materials = InventoryMaterial::all();

        return view('traslados.edit', compact(
            'traslado',
            'users',
            'centros',
            'sedes',
            'units',
            'materials'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $traslado = NeedTransfer::findOrFail($id);

        $request->merge([
            'centro_inicial_id' => $request->input('inicial_centro_id'),
            'sede_inicial_id'   => $request->input('inicial_sede_id'),
            'centro_final_id'   => $request->input('final_centro_id'),
            'sede_final_id'     => $request->input('final_sede_id'),
            'unidad_id'         => $request->input('unidad_id'),
            'subunidad_id'      => $request->input('subunidad_id'),
        ]);

        $data = $request->validate([
            'unidad_id' => 'required|exists:dependency_units,dependency_unit_id',
            'subunidad_id' => 'required|exists:dependency_subunits,subunit_id',
            'centro_inicial_id' => 'nullable|exists:centros,id',
            'sede_inicial_id' => 'nullable|exists:sedes,id',
            'centro_final_id' => 'nullable|exists:centros,id',
            'sede_final_id' => 'nullable|exists:sedes,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'descripcion' => 'nullable|string',
            'nivel_riesgo' => 'required|string',
            'nivel_complejidad' => 'required|string',
            'presupuesto_solicitado' => 'nullable|numeric',
            'presupuesto_aceptado' => 'nullable|numeric',
            'requiere_personal' => 'boolean',
            'requiere_materiales' => 'boolean',
            'status' => 'required|in:pendiente,completada', // <-- validación aquí
        ]);

        $traslado->update($data);

        // Actualizar personal
        if ($request->filled('personal')) {
            $syncData = [];
            foreach ($request->personal as $p) {
                $syncData[$p['id']] = ['cargo' => $p['cargo'] ?? null];
            }
            $traslado->personal()->sync($syncData);
        } else {
            $traslado->personal()->detach();
        }

        // Actualizar materiales
        if ($request->filled('materiales')) {
            $syncData = [];
            foreach ($request->materiales as $m) {
                $syncData[$m['inventory_material_id']] = [
                    'cantidad' => $m['cantidad'],
                    'tipo' => $m['tipo'] ?? null,
                ];
            }
            $traslado->materiales()->sync($syncData);
        } else {
            $traslado->materiales()->detach();
        }

        return redirect()->route('traslados.index')
            ->with('success', 'Traslado actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
