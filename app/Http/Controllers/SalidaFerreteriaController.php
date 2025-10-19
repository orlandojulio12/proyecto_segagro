<?php
// app/Http/Controllers/SalidaFerreteriaController.php

namespace App\Http\Controllers;

use App\Models\Ferreteria\SalidaFerreteria;
use App\Models\Ferreteria\SalidaFerreteriaDetail;
use App\Models\Centro;
use App\Models\Sede;
use App\Models\User;
use App\Models\InventoryMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalidaFerreteriaController extends Controller
{
    public function index()
    {
        $salidas = SalidaFerreteria::with(['user', 'centro', 'sede', 'detalles'])
            ->latest('fecha_salida')
            ->get();
        
        return view('salida_ferreteria.index', compact('salidas'));
    }

    public function create()
    {
        $centros = Centro::all();
        $sedes = Sede::all();
        $users = User::all();
        // Obtener todos los materiales del inventario disponibles
        $materiales = InventoryMaterial::with('inventory.sede')
            ->where('material_quantity', '>', 0)
            ->get();
        
        return view('salida_ferreteria.create', compact('centros', 'sedes', 'users', 'materiales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'centro_id' => 'required|exists:centros,id',
            'sede_id' => 'required|exists:sedes,id',
            'fecha_salida' => 'required|date',
            'observaciones' => 'nullable|string',
            'f14' => 'nullable|string',
            
            'materiales' => 'required|array|min:1',
            'materiales.*.inventory_material_id' => 'required|exists:inventory_materials,id',
            'materiales.*.cantidad' => 'required|numeric|min:0.01',
            'materiales.*.observacion' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request) {
            // Crear la salida de ferretería
            $salida = SalidaFerreteria::create([
                'user_id' => $request->user_id,
                'centro_id' => $request->centro_id,
                'sede_id' => $request->sede_id,
                'observaciones' => $request->observaciones,
                'fecha_salida' => $request->fecha_salida,
                'f14' => $request->f14,
            ]);

            // Crear los detalles y actualizar inventario
            foreach ($request->materiales as $mat) {
                // Crear detalle
                SalidaFerreteriaDetail::create([
                    'salida_ferreteria_id' => $salida->id,
                    'inventory_material_id' => $mat['inventory_material_id'],
                    'cantidad' => $mat['cantidad'],
                    'observacion' => $mat['observacion'] ?? null,
                ]);

                // Actualizar cantidad en inventario
                $material = InventoryMaterial::find($mat['inventory_material_id']);
                if ($material) {
                    $material->material_quantity -= $mat['cantidad'];
                    $material->save();
                }
            }
        });

        return redirect()->route('salida_ferreteria.index')
            ->with('success', 'Salida de ferretería registrada exitosamente');
    }

    public function show(SalidaFerreteria $salidaFerreteria)
    {
        $salidaFerreteria->load(['user', 'centro', 'sede', 'detalles.material']);
        return view('salida_ferreteria.show', compact('salidaFerreteria'));
    }

    public function edit(SalidaFerreteria $salidaFerreteria)
    {
        $centros = Centro::all();
        $sedes = Sede::all();
        $users = User::all();
        $materiales = InventoryMaterial::with('inventory.sede')->get();
        
        $salidaFerreteria->load(['detalles.material']);
        
        return view('salida_ferreteria.edit', compact('salidaFerreteria', 'centros', 'sedes', 'users', 'materiales'));
    }

    public function update(Request $request, SalidaFerreteria $salidaFerreteria)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'centro_id' => 'required|exists:centros,id',
            'sede_id' => 'required|exists:sedes,id',
            'fecha_salida' => 'required|date',
            'observaciones' => 'nullable|string',
            'f14' => 'nullable|string',
            
            'materiales' => 'required|array|min:1',
            'materiales.*.inventory_material_id' => 'required|exists:inventory_materials,id',
            'materiales.*.cantidad' => 'required|numeric|min:0.01',
            'materiales.*.observacion' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $salidaFerreteria) {
            // Revertir cantidades anteriores
            foreach ($salidaFerreteria->detalles as $detalle) {
                $material = $detalle->material;
                if ($material) {
                    $material->material_quantity += $detalle->cantidad;
                    $material->save();
                }
            }

            // Actualizar la salida
            $salidaFerreteria->update([
                'user_id' => $request->user_id,
                'centro_id' => $request->centro_id,
                'sede_id' => $request->sede_id,
                'observaciones' => $request->observaciones,
                'fecha_salida' => $request->fecha_salida,
                'f14' => $request->f14,
            ]);

            // Eliminar detalles anteriores
            $salidaFerreteria->detalles()->delete();

            // Crear nuevos detalles y actualizar inventario
            foreach ($request->materiales as $mat) {
                SalidaFerreteriaDetail::create([
                    'salida_ferreteria_id' => $salidaFerreteria->id,
                    'inventory_material_id' => $mat['inventory_material_id'],
                    'cantidad' => $mat['cantidad'],
                    'observacion' => $mat['observacion'] ?? null,
                ]);

                $material = InventoryMaterial::find($mat['inventory_material_id']);
                if ($material) {
                    $material->material_quantity -= $mat['cantidad'];
                    $material->save();
                }
            }
        });

        return redirect()->route('salida_ferreteria.index')
            ->with('success', 'Salida de ferretería actualizada exitosamente');
    }

    public function destroy(SalidaFerreteria $salidaFerreteria)
    {
        DB::transaction(function () use ($salidaFerreteria) {
            // Revertir cantidades al inventario
            foreach ($salidaFerreteria->detalles as $detalle) {
                $material = $detalle->material;
                if ($material) {
                    $material->material_quantity += $detalle->cantidad;
                    $material->save();
                }
            }

            $salidaFerreteria->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Salida eliminada exitosamente'
        ]);
    }

    // Método auxiliar para obtener materiales por sede (AJAX)
    public function getMaterialesBySede($sedeId)
    {
        $materiales = InventoryMaterial::with('inventory')
            ->whereHas('inventory', function($query) use ($sedeId) {
                $query->where('sede_id', $sedeId);
            })
            ->where('material_quantity', '>', 0)
            ->get();
        
        return response()->json($materiales);
    }
}