<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CatalogProductImport;
use Illuminate\Support\Facades\Log;

class CatalogProductSeeder extends Seeder
{
    public function run(): void
    {
        // Opcional: eleva límite de memoria temporalmente
        ini_set('memory_limit', '512M');

        $file = database_path('seeders/data/catalogo.xls');

        // Si el archivo puede ser muy grande, se recomienda usar Excel::queueImport (requiere ShouldQueue)
        try {
            Excel::import(new CatalogProductImport, $file);
            $this->command->info('Catalog import finished.');
        } catch (\Exception $e) {
            // log y mostrar
            Log::error('Catalog import error: '.$e->getMessage());
            $this->command->error('Catalog import failed: '.$e->getMessage());
        }
    }
}
