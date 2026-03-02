<?php

namespace Database\Seeders;

use App\Models\Dependency\DependencyUnit;
use Illuminate\Database\Seeder;

class DependencyUnitsSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['short_name' => 'DESPLAZADOS', 'full_name' => 'Desplazados', 'description' => null],
            ['short_name' => 'AGENCIA PÚBLICA DE EMPLEO', 'full_name' => 'Agencia Pública de Empleo', 'description' => null],
            ['short_name' => 'EMPRENDIMIENTO', 'full_name' => 'Emprendimiento', 'description' => null],
            ['short_name' => 'ADMINISTRACIÓN DE LOS PROCESOS', 'full_name' => 'Administración de los Procesos', 'description' => null],
            ['short_name' => 'FORMACIÓN', 'full_name' => 'Formación', 'description' => null],
            ['short_name' => 'FORMACIÓN (COMPETENCIAS LABORALES / CAMPESENA / FULL POPULAR)', 'full_name' => 'Formación - Competencias Laborales y Economía Popular', 'description' => null],
            ['short_name' => 'INNOVACIÓN', 'full_name' => 'Innovación', 'description' => null],
            ['short_name' => 'VIVIENDA, PENSIONES Y CESANTÍAS', 'full_name' => 'Vivienda, Pensiones y Cesantías', 'description' => null],
            ['short_name' => 'INFRAESTRUCTURA', 'full_name' => 'Infraestructura', 'description' => null],
        ];

        foreach ($units as $unit) {
            DependencyUnit::create($unit);
        }
    }
}
