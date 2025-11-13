<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContractTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('contract_types')->insert([
            [
                'type_name' => 'Infraestructura',
                'description' => 'Contratos relacionados con obras civiles, mantenimiento, adecuación y mejoramiento de la infraestructura física de la institución.',
                'dependencia_id' => 1,
            ],
            [
                'type_name' => 'Contrataciones',
                'description' => 'Contratos destinados a la adquisición de bienes, servicios o consultorías necesarias para el funcionamiento institucional.',
                'dependencia_id' => 2,
            ],
            [
                'type_name' => 'Ferretería',
                'description' => 'Contratos orientados a la compra de materiales y suministros de ferretería requeridos para labores de mantenimiento o construcción.',
                'dependencia_id' => 3,
            ],
        ]);
    }
}
