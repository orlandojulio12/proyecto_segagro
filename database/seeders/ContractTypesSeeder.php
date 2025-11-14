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
        $infra = DB::table('dependencias')->where('nombre', 'Infraestructura')->value('id');
        $contrat = DB::table('dependencias')->where('nombre', 'Contratación')->value('id');

        DB::table('contract_types')->insert([
            [
                'type_name' => 'Infraestructura',
                'description' => 'Contratos relacionados con obras civiles, mantenimiento, adecuación y mejoramiento de la infraestructura física de la institución.',
                'dependencia_id' => $infra,
            ],
            [
                'type_name' => 'Contrataciones',
                'description' => 'Contratos destinados a la adquisición de bienes, servicios o consultorías necesarias para el funcionamiento institucional.',
                'dependencia_id' => $contrat,
            ],
            [
                'type_name' => 'Ferretería',
                'description' => 'Contratos orientados a la compra de materiales y suministros de ferretería requeridos para labores de mantenimiento o construcción.',
                'dependencia_id' => $infra, // o null si prefieres
            ],
        ]);
    }
}
