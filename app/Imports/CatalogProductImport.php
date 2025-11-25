<?php

namespace App\Imports;

use App\Models\Inventario\CatalogProduct;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class CatalogProductImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts, SkipsEmptyRows
{
    /**
     * Map each row to the model
     */
    public function model(array $row)
    {
        return new CatalogProduct([
            'type_code'           => $row['codigo_tipo'] ?? null,
            'type_name'           => $row['nombre_tipo'] ?? null,
            'segment'             => $row['segmento'] ?? null,
            'segment_description' => $row['descripcion_segmento'] ?? null,
            'family_code'         => $row['codigo_familia'] ?? null,
            'family_name'         => $row['nombre_familia'] ?? null,
            'class_code'          => $row['codigo_clase'] ?? null,
            'class_name'          => $row['nombre_clase'] ?? null,
            'useful_life'         => $row['vida_util'] ?? null,
            'sku'                 => $row['sku'] ?? null,
            'sku_description'     => $row['sku_descripcion'] ?? null,
            'consecutive'         => $row['consecutivo'] ?? null,
            'element_description' => $row['descripcion_elemento'] ?? null,
        ]);
    }

    /**
     * Número de filas por chunk (lee y procesa este bloque por vez).
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * Inserciones por lotes (mejora rendimiento al insertar a BD).
     */
    public function batchSize(): int
    {
        return 1000;
    }
}
