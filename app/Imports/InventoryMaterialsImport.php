<?php

namespace App\Imports;

use App\Models\InventoryMaterial;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InventoryMaterialsImport implements ToCollection
{
    protected $inventoryId;
    protected $rows = [];

    public function __construct($inventoryId)
    {
        $this->inventoryId = $inventoryId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows->skip(1) as $row) { // saltar cabecera
            $material = [
                'material_name'     => $row[0],
                'material_quantity' => (int) $row[1],
                'material_type'     => $row[2],
                'material_price'    => (float) $row[3],
                'iva_percentage'    => (int) filter_var($row[4], FILTER_SANITIZE_NUMBER_INT),
            ];

            // Guardar en BD
            InventoryMaterial::create([
                'inventory_id'      => $this->inventoryId,
                'material_name'     => $material['material_name'],
                'material_quantity' => $material['material_quantity'],
                'material_type'     => $material['material_type'],
                'material_price'    => $material['material_price'],
                'iva_percentage'    => $material['iva_percentage'],
            ]);

            // Guardar en array para devolver al frontend
            $this->rows[] = $material + [
                'total_without_tax' => $material['material_quantity'] * $material['material_price'],
                'total_with_tax'    => ($material['material_quantity'] * $material['material_price']) * (1 + $material['iva_percentage'] / 100),
            ];
        }
    }

    public function getImportedRows()
    {
        return $this->rows;
    }
}

