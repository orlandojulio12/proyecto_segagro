<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HiringModalitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        DB::table('hiring_modalities')->insert([
            [
                'modality_name' => 'Concurso de Méritos Abierto',
                'description' => 'Proceso de selección donde se evalúan méritos y experiencia para elegir al contratista más idóneo según criterios técnicos y académicos.',
            ],
            [
                'modality_name' => 'Mínima Cuantía',
                'description' => 'Procedimiento simplificado para contratar bienes o servicios cuyo valor no supera el monto definido por la ley para mínima cuantía.',
            ],
            [
                'modality_name' => 'Contratación Directa',
                'description' => 'Modalidad utilizada cuando, por razones justificadas, no es necesario realizar un proceso competitivo de selección.',
            ],
            [
                'modality_name' => 'Selección Abreviada Menor Cuantía',
                'description' => 'Proceso ágil de selección utilizado para contratos de menor valor económico, garantizando transparencia y eficiencia.',
            ],
            [
                'modality_name' => 'Selección Abreviada Subasta Inversa',
                'description' => 'Modalidad en la que los oferentes compiten reduciendo sus precios en tiempo real hasta alcanzar la oferta más conveniente.',
            ],
        ]);
    }
}
