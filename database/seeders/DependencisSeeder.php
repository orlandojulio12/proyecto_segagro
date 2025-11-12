<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DependencisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        DB::table('dependencias')->insert([
            [
                'nombre' => 'Infraestructura',
                'descripcion' => 'Dependencia encargada de la gestión, mantenimiento y desarrollo de la infraestructura física y tecnológica de la institución.',
            ],
            [
                'nombre' => 'Contratación',
                'descripcion' => 'Dependencia responsable de los procesos de contratación, adquisición de bienes y servicios, y cumplimiento de la normatividad vigente.',
            ],
        ]);
    }
}
