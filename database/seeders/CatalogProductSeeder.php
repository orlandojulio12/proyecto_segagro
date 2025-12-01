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
        ini_set('memory_limit', '512M');

        try {
            // 1 = devolutivo
            Excel::import(
                new CatalogProductImport(1),
                database_path('seeders/data/Catalogo_Devolutivos.xls')
            );

            // 2 = consumo
            Excel::import(
                new CatalogProductImport(2),
                database_path('seeders/data/Catalogo_Consumo.xls')
            );

            $this->command->info('Catalog import finished.');
        } catch (\Exception $e) {
            Log::error('Catalog import error: ' . $e->getMessage());
            $this->command->error('Catalog import failed: ' . $e->getMessage());
        }
    }
}
