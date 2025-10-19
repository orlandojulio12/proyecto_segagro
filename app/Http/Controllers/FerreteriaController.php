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
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MaterialesTemplateExport;

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

    public function downloadTemplate()
    {
        return Excel::download(
            new MaterialesTemplateExport(), 
            'plantilla_materiales_ferreteria.xlsx'
        );
    }

    /**
     * Importar materiales desde Excel
     */
    /**
 * Importar materiales desde Excel (para el formulario CREATE)
 * NO guarda en BD, solo devuelve los datos al frontend
 */
public function importMaterials(Request $request)
{
    try {
        // Validar solo el archivo
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240', // Máximo 10MB
        ]);

        $file = $request->file('file');

        // Leer archivo Excel en array (sin guardar en BD)
        $rows = Excel::toArray([], $file);

        if (empty($rows) || count($rows[0]) === 0) {
            return response()->json([
                'error' => 'El archivo está vacío o tiene formato inválido.'
            ], 422);
        }

        $data = [];
        
        // IMPORTANTE: Saltar las primeras 11 filas (instrucciones + encabezado)
        foreach ($rows[0] as $index => $row) {
            if ($index < 11) continue; // Saltar instrucciones
            
            // Validar que la fila no esté vacía
            if (empty($row[0]) || empty($row[1])) continue;

            $quantity = (int)($row[1] ?? 0);
            $price = (float)($row[3] ?? 0);
            $iva = isset($row[4]) ? (int)str_replace('%', '', $row[4]) : 0;
            
            $totalWithoutTax = $quantity * $price;
            $totalWithTax = $totalWithoutTax * (1 + $iva / 100);

            $data[] = [
                'material_name'      => trim($row[0] ?? ''),
                'material_quantity'  => $quantity,
                'material_type'      => trim($row[2] ?? ''),
                'material_price'     => $price,
                'iva_percentage'     => $iva,
                'total_without_tax'  => round($totalWithoutTax, 2),
                'total_with_tax'     => round($totalWithTax, 2),
                'observations'       => isset($row[5]) ? trim($row[5]) : '',
            ];
        }

        if (empty($data)) {
            return response()->json([
                'error' => 'No se encontraron materiales válidos en el archivo. Asegúrate de completar los datos a partir de la fila 12.'
            ], 422);
        }

        return response()->json($data, 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'error' => 'El archivo debe ser un Excel válido (.xlsx o .xls)'
        ], 422);
        
    } catch (\Throwable $e) {
        \Log::error("Error importando materiales: " . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'error' => 'Error al importar: ' . $e->getMessage()
        ], 500);
    }
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
