<?php

namespace App\Http\Controllers\Traslado;

use App\Http\Controllers\Controller;
use App\Models\Centro;
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
        $materiales = InventoryMaterial::all();
        $centros = Centro::all();
        $sedes = Sede::all();
        $dependencias = InventorySede::all();
        $materials = InventoryMaterial::all();
        return view('traslados.create', compact('users', 'centros', 'sedes', 'dependencias', 'materials'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            //'dependencia_id' => 'nullable|exists:inventory_sedes,id',
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
        $data['dependencia_id'] = 1;
        $data['user_id'] = Auth::id();
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
