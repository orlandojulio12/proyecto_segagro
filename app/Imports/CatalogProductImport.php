<?php

namespace App\Imports;

use App\Models\Inventario\CatalogProduct;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class CatalogProductImport implements 
    ToModel, 
    WithHeadingRow, 
    WithChunkReading, 
    SkipsEmptyRows
{
    protected int $typeCatalogo;

    public function __construct(int $typeCatalogo)
    {
        $this->typeCatalogo = $typeCatalogo;
    }

    public function model(array $row)
    {
        try {
            return new CatalogProduct([
                'type_code'           => $row['codigo_tipo'] ?? null,
                'type_name'           => $row['nombre_tipo'] ?? null,
                'segment'             => $row['segmento'] ?? null,
                'segment_description' => $row['descripcion_segmento'] ?? null,
                'family_code'         => $row['codigo_familia'] ?? null,
                'family_name'         => $row['nombre_familia'] ?? null,
                'class_code'          => $row['codigo_clase'] ?? null,
                'class_name'          => $row['nombre_clase'] ?? null,
                'useful_life'         => isset($row['vida_util']) && is_numeric($row['vida_util'])
                                            ? $row['vida_util']
                                            : null,
                'sku'                 => $row['sku'] ?? null,
                'sku_description'     => $row['sku_descripcion'] ?? null,
                'consecutive'         => isset($row['consecutivo']) && is_numeric($row['consecutivo'])
                                            ? $row['consecutivo']
                                            : null,
                'element_description' => $row['descripcion_elemento'] ?? null,

                // Se asigna según el tipo del catálogo
                'type_catalogo'       => $this->typeCatalogo,
            ]);

        } catch (\Throwable $e) {

            $identifier = $row['sku'] ?? ($row['codigo_tipo'] ?? 'SIN IDENTIFICAR');

            throw new \Exception(
                "❌ Error en fila identificada por [{$identifier}]: " . $e->getMessage()
            );
        }
    }

    public function chunkSize(): int
    {
        return 500;
    }
}

