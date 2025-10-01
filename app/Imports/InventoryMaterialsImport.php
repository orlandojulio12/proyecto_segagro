<?php

namespace App\Imports;

use App\Models\InventoryMaterial;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InventoryMaterialsImport implements ToModel, WithHeadingRow
{
    protected $inventoryId;

    public function __construct($inventoryId)
    {
        $this->inventoryId = $inventoryId;
    }

    public function model(array $row)
    {
        return new InventoryMaterial([
            'inventory_id'       => $this->inventoryId,
            'material_name'      => $row['material_name'],
            'material_quantity'  => $row['material_quantity'],
            'material_type'      => $row['material_type'] ?? null,
            'material_price'     => $row['material_price'] ?? 0,
            'iva_percentage'     => $row['iva_percentage'] ?? 0,
            'total_without_tax'  => $row['total_without_tax'] ?? 0,
            'total_with_tax'     => $row['total_with_tax'] ?? 0,
            'observations'       => $row['observations'] ?? null,
        ]);
    }
}
