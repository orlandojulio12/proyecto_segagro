<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CatalogProductImport;

class CatalogProductSeeder extends Seeder
{
    public function run(): void
    {
        Excel::import(new CatalogProductImport, database_path('seeders/data/catalogo.xlsx'));
    }
}
