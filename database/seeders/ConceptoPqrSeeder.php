<?php

namespace Database\Seeders;

use App\Models\Pqr\ConceptoPqr;
use App\Models\Pqr\DependenciaPqr;
use Illuminate\Database\Seeder;

class ConceptoPqrSeeder extends Seeder
{
    public function run(): void
    {
        $conceptos = [
            'Formación' => [
                'Solicitud de Retiro',
                'Solicitud de Reintegro',
                'Solicitud de Aplazamiento',
                'Solicitud de Certificado de Estudio',
                'Solicitud de Certificado de Estudio con notas',
                'Solicitud Certificado de Buena Conducta',
                'Solicitud Diploma Estudio',
                'Otros'
            ],
            'GAA' => [
                'Certificado Laboral - Contratista',
                'Legalización de comisiones - Viáticos',
                'Otros'
            ],
            'GAE' => [
                'Solicitud Diploma Estudio',
                'Solicitud Cambio de Documento de Identidad',
                'Usurpación en Documento de identidad',
                'Creación Usuario Sofia Plus',
                'Inconsistencia en Sofia Plus',
                'Inconsistencia Proceso de Selección',
                'Inconsistencia en Matricula - Aprendiz',
                'Solicitud Certificado Contenido Programático',
                'Solicitud Certificado Contenido Programático con intensidad horaria',
                'Otros'
            ]
        ];

        foreach ($conceptos as $depName => $conceptList) {
            $dep = DependenciaPqr::where('name', $depName)->first();
            foreach ($conceptList as $concept) {
                ConceptoPqr::create([
                    'name' => $concept,
                    'dependencia_id' => $dep->id_dependencia
                ]);
            }
        }
    }
}