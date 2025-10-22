<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Inventario\SalidaFerreteria;
use App\Models\Inventario\SalidaFerreteriaDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalidaFerreteriaController extends Controller
{
    public function index()
    {
        $salidas = SalidaFerreteria::with(['user', 'centro', 'sede'])->latest()->get();
        return view('salida_ferreteria.index', compact('salidas'));
    }

    public function create()
    {
        $users = \App\Models\User::all();
        $centros = \App\Models\Centro::all();
        $sedes = \App\Models\Sede::all();
        $materiales = \App\Models\InventoryMaterial::all();

        return view('salida_ferreteria.create', compact('users', 'centros', 'sedes', 'materiales'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'centro_id' => 'required|exists:centros,id',
            'sede_id' => 'required|exists:sedes,id',
            'fecha_salida' => 'required|date',
            'observaciones' => 'nullable|string',
            'f14' => 'nullable|string|max:255',
            'materiales' => 'required|array',
            'materiales.*.inventory_material_id' => 'required|exists:inventory_materials,id',
            'materiales.*.cantidad' => 'required|numeric|min:0.1',
            'materiales.*.observacion' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $salida = SalidaFerreteria::create([
                'user_id' => $validated['user_id'],
                'centro_id' => $validated['centro_id'],
                'sede_id' => $validated['sede_id'],
                'observaciones' => $validated['observaciones'] ?? null,
                'fecha_salida' => $validated['fecha_salida'],
                'f14' => $validated['f14'] ?? null,
            ]);

            foreach ($validated['materiales'] as $detalle) {
                SalidaFerreteriaDetail::create([
                    'salida_ferreteria_id' => $salida->id,
                    'inventory_material_id' => $detalle['inventory_material_id'],
                    'cantidad' => $detalle['cantidad'],
                    'observacion' => $detalle['observacion'] ?? null,
                ]);
            }
        });

        return redirect()->route('salida_ferreteria.index')->with('success', 'Salida de ferretería registrada correctamente.');
    }

    public function show(SalidaFerreteria $salida)
    {
        $salida->load(['detalles.material', 'user', 'centro', 'sede']);
        return view('salida_ferreteria.show', compact('salida'));
    }

    public function destroy(SalidaFerreteria $salida)
    {
        $salida->delete();
        return redirect()->route('salida_ferreteria.index')->with('success', 'Salida eliminada correctamente.');
    }
  }