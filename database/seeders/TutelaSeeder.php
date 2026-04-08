<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DependenciaPqr;
use App\Models\ConceptoPqr;
use App\Models\Pqr\ConceptoPqr as PqrConceptoPqr;
use App\Models\Pqr\DependenciaPqr as PqrDependenciaPqr;

class TutelaSeeder extends Seeder
{
    public function run(): void
    {
        // Crear la dependencia Subdirección
        $subdireccion = PqrDependenciaPqr::firstOrCreate(
            ['name' => 'Subdirección']
        );

        // Crear los conceptos de tutela
        $conceptos = [
            'Tutela',
            'Respuesta Tutela',
            'Fallo Tutela'
        ];

        foreach ($conceptos as $concepto) {
            PqrConceptoPqr::firstOrCreate([
                'name' => $concepto,
                'dependencia_id' => $subdireccion->id_dependencia
            ]);
        }
    }
}