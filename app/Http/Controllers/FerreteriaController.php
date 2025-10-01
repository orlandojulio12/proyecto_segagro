<?php
// app/Http/Controllers/FerreteriaController.php

namespace App\Http\Controllers;

use App\Imports\InventoryMaterialsImport;
use App\Models\InventorySede;
use App\Models\InventoryMaterial;
use App\Models\Sede;
use App\Models\Centro;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class FerreteriaController extends Controller
{
    public function index()
    {
        $inventories = InventorySede::with(['sede.centro', 'staff', 'materials'])->latest('record_date')->get();
        return view('ferreteria.index', compact('inventories'));
    }

    public function create()
    {
        $centros = Centro::all();
        $sedes = Sede::all();
        $users = User::all();
        return view('ferreteria.create', compact('centros', 'sedes', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sede_id' => 'required|exists:sedes,id',
            'responsible_department' => 'required|string|max:255',
            'staff_name' => 'required|exists:users,id',
            'inventory_description' => 'required|string',
            'image_inventory' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            'materials' => 'required|array|min:1',
            'materials.*.material_name' => 'required|string|max:255',
            'materials.*.material_quantity' => 'required|integer|min:1',
            'materials.*.material_type' => 'nullable|string|max:100',
            'materials.*.material_price' => 'required|numeric|min:0',
            'materials.*.iva_percentage' => 'required|in:0,5,12,19',
            'materials.*.observations' => 'nullable|string|max:500',
        ]);



        DB::transaction(function () use ($request) {
            $imagePath = null;

            // Manejar la imagen si se sube
            if ($request->hasFile('image_inventory')) {
                $image = $request->file('image_inventory');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('ferreteria', $imageName, 'public');
            }

            // Crear inventario
            $inventory = InventorySede::create([
                'sede_id' => $request->sede_id,
                'responsible_department' => $request->responsible_department,
                'staff_name' => $request->staff_name,
                'image_inventory' => $imagePath,
                'inventory_description' => $request->inventory_description,
                'record_date' => now()
            ]);

            // Crear materiales
            foreach ($request->materials as $material) {
                $quantity = $material['material_quantity'];
                $price = $material['material_price'];
                $iva = $material['iva_percentage'];

                // Calcular totales
                $totalWithoutTax = $quantity * $price;
                $totalWithTax = $totalWithoutTax + ($totalWithoutTax * $iva / 100);

                InventoryMaterial::create([
                    'inventory_id' => $inventory->id,
                    'material_name' => $material['material_name'],
                    'material_quantity' => $quantity,
                    'material_type' => $material['material_type'] ?? null,
                    'material_price' => $price,
                    'iva_percentage' => $iva,
                    'total_without_tax' => $totalWithoutTax,
                    'total_with_tax' => $totalWithTax,
                    'observations' => $material['observations'] ?? null,
                ]);
            }
        });

        return redirect()->route('ferreteria.index')->with('success', 'Inventario creado exitosamente');
    }

    public function importMaterials(Request $request, $inventory)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $path = $request->file('file')->getRealPath();

        // Leer con Laravel Excel
        $data = Excel::toArray([], $path);

        $rows = $data[0]; // Primera hoja
        $materials = [];

        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Saltar encabezado

            $materials[] = [
                'material_name' => $row[0] ?? '',
                'material_quantity' => $row[1] ?? 0,
                'material_type' => $row[2] ?? '',
                'material_price' => $row[3] ?? 0,
                'material_iva' => $row[4] ?? 0,
                'material_total_sin_iva' => $row[5] ?? 0,
                'material_total_con_iva' => $row[6] ?? 0,
                'material_observations' => $row[7] ?? '',
            ];
        }

        return response()->json($materials);
    }


    public function show(InventorySede $inventory)
    {
        $inventory->load(['sede.centro', 'staff', 'materials']);
        return view('ferreteria.show', compact('inventory'));
    }

    public function edit(InventorySede $inventory)
    {
        $centros = Centro::all();
        $sedes = Sede::all();
        $users = User::all();
        $inventory->load(['sede.centro', 'materials']);
        return view('ferreteria.edit', compact('inventory', 'centros', 'sedes', 'users'));
    }

    public function update(Request $request, InventorySede $inventory)
    {
        $request->validate([
            'sede_id' => 'required|exists:sedes,id',
            'responsible_department' => 'required|string|max:255',
            'staff_name' => 'required|exists:users',
            'inventory_description' => 'required|string',
            'image_inventory' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'materials' => 'required|array|min:1',
            'materials.*.material_name' => 'required|string|max:255',
            'materials.*.material_quantity' => 'required|integer|min:1',
            'materials.*.material_type' => 'nullable|string|max:100',
            'materials.*.material_price' => 'nullable|numeric|min:0'
        ]);

        DB::transaction(function () use ($request, $inventory) {
            $imagePath = $inventory->image_inventory; // Mantener imagen actual

            // Manejar nueva imagen si se sube
            if ($request->hasFile('image_inventory')) {
                // Eliminar imagen anterior si existe
                if ($inventory->image_inventory && \Storage::disk('public')->exists($inventory->image_inventory)) {
                    \Storage::disk('public')->delete($inventory->image_inventory);
                }

                $image = $request->file('image_inventory');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('ferreteria', $imageName, 'public');
            }

            // Actualizar inventario
            $inventory->update([
                'sede_id' => $request->sede_id,
                'responsible_department' => $request->responsible_department,
                'staff_name' => $request->staff_name,
                'image_inventory' => $imagePath,
                'inventory_description' => $request->inventory_description
            ]);

            // Eliminar materiales existentes
            $inventory->materials()->delete();

            // Crear nuevos materiales
            foreach ($request->materials as $material) {
                InventoryMaterial::create([
                    'inventory_id' => $inventory->id,
                    'material_name' => $material['material_name'],
                    'material_quantity' => $material['material_quantity'],
                    'material_type' => $material['material_type'] ?? null,
                    'material_price' => $material['material_price'] ?? null
                ]);
            }
        });

        return redirect()->route('ferreteria.index')->with('success', 'Inventario actualizado exitosamente');
    }

    public function destroy(InventorySede $inventory)
    {
        DB::transaction(function () use ($inventory) {
            $inventory->materials()->delete();
            $inventory->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Inventario eliminado exitosamente'
        ]);
    }

    public function getSedesByCentro($centroId)
    {
        $sedes = Sede::where('centro_id', $centroId)->get();
        return response()->json($sedes);
    }
}
