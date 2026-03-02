<?php

namespace Database\Seeders;

use App\Models\Dependency\DependencySubunit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DependencySubunitsSeeder extends Seeder
{
    public function run(): void
    {
        $subunits = [
            // ---------------------------
            // 1. DESPLAZADOS
            // ---------------------------
            [1, '00', 'ACCIONES REGULARES Y DEMÁS PROYECTOS PARA DESPACHOS Y DIRECCIÓN GENERAL', null],
            [1, '10', 'ACCIONES REGULARES Y DEMÁS PROYECTOS', null],

            // ---------------------------
            // 2. AGENCIA PÚBLICA DE EMPLEO
            // ---------------------------
            [2, '00', 'ACCIONES REGULARES Y DEMÁS PROYECTOS PARA DESPACHOS Y DIRECCIÓN GENERAL', null],
            [2, '10', 'ACCIONES REGULARES Y DEMÁS PROYECTOS', null],

            // ---------------------------
            // 3. EMPRENDIMIENTO
            // ---------------------------
            [3, '39', 'EMPRENDIMIENTO', null],
            [3, '55', 'FONDO EMPRENDER', null],

            // ---------------------------
            // 4. ADMINISTRACIÓN DE LOS PROCESOS
            // ---------------------------
            [4, '00', 'ACCIONES REGULARES Y DEMÁS PROYECTOS PARA DESPACHOS Y DIRECCIÓN GENERAL (Servicios Públicos, Impuestos, Jurídica, Contratación, Planeación)', null],
            [4, '10', 'ACCIONES REGULARES Y DEMÁS PROYECTOS', null],
            [4, '40', 'APORTES (Cobro coactivo, Fiscalizador, Recaudo...)', null],

            // ---------------------------
            // 5. FORMACIÓN
            // ---------------------------
            [5, '00', 'ACCIONES REGULARES Y DEMÁS PROYECTOS PARA DESPACHOS Y DIRECCIÓN GENERAL (Aseo, Vigilancia, Seguridad)', null],
            [5, '10', 'ACCIONES REGULARES Y DEMÁS PROYECTOS (Aseo, Vigilancia, Seguridad)', null],
            [5, '27', 'MODERNIZACIÓN DE AMBIENTES (TIC´s)', null],

            // -----------------------------------------------------
            // 6. FORMACIÓN (COMPETENCIAS LABORALES / ECONOMÍA POPULAR)
            // -----------------------------------------------------
            [6, '00', 'ACCIONES REGULARES Y DEMÁS PROYECTOS PARA DESPACHOS Y DIRECCIÓN GENERAL (SPI Calidad, Servicios Públicos)', null],
            [6, '10', 'ACCIONES REGULARES Y DEMÁS PROYECTOS', null],

            [6, '09', 'SALUD OCUPACIONAL', null],
            [6, '11', 'ARTICULACIÓN CON LA MEDIA', null],
            [6, '14', 'ESCUELA NACIONAL DE INSTRUCTORES', null],
            [6, '18', 'FONDO DE LA INDUSTRIA DE LA CONSTRUCCIÓN - FIC', null],
            [6, '20', 'DISEÑO CURRICULAR', null],
            [6, '25', 'CUALIFICACIONES', null],
            [6, '27', 'MODERNIZACIÓN DE AMBIENTES (Mantenimiento, Vehículos, Maquinaria)', null],
            [6, '28', 'CERTIFICACIÓN DE COMPETENCIAS LABORALES', null],
            [6, '34', 'PRODUCCIÓN EN CENTROS DE FORMACIÓN', null],
            [6, '38', 'SENA EMPRENDE RURAL (Economía campesina y SER)', null],
            [6, '42', 'BIENESTAR ALUMNOS', null],
            [6, '43', 'BIENESTAR FUNCIONARIOS', null],
            [6, '44', 'APOYOS DE SOSTENIMIENTO APRENDICES', null],
            [6, '45', 'SERVICIOS PRESTADOS A LA FORMACIÓN PROFESIONAL', null],
            [6, '63', 'INTERNACIONALIZACIÓN', null],
            [6, '64', 'FORMACIÓN ESPECIALIZADA ECONOMÍA CAMPESINA (FEEC / FEEP)', null],
            [6, '83', 'EXTENSIONISMO TECNOLÓGICO', null],
            [6, '84', 'EVALUACIÓN COMPETENCIAS LABORALES - ECONOMÍA POPULAR', null],
            [6, '85', 'SERVICIOS PRESTADOS - ECONOMÍA POPULAR', null],
            [6, '86', 'CERTIFICACIÓN COMPETENCIAS LABORALES - ECONOMÍA POPULAR', null],
            [6, '90', 'ACCIONES REGULARES ECONOMÍA CAMPESINA Y POPULAR (Aulas móviles)', null],

            // ---------------------------
            // 7. INNOVACIÓN
            // ---------------------------
            [7, '23', 'ACTUALIZACIÓN Y MODERNIZACIÓN TECNOLÓGICA DE LOS CENTROS', null],
            [7, '61', 'INVESTIGACIÓN PARA LA FORMACIÓN PROFESIONAL INTEGRAL', null],
            [7, '62', 'CONCURSOS DE RETROALIMENTACIÓN (Formula Eco, Worldskills...)', null],
            [7, '64', 'PROGRAMA NACIONAL DE FORMACIÓN ESPECIALIZADA', null],
            [7, '65', 'CULTURA DE LA INNOVACIÓN Y LA COMPETITIVIDAD', null],
            [7, '66', 'INVESTIGACIÓN APLICADA Y SEMILLEROS', null],
            [7, '68', 'SERVICIOS TECNOLÓGICOS', null],
            [7, '69', 'PARQUES TECNOLÓGICOS - RED TECNOPARQUE', null],
            [7, '70', 'TECNOACADEMIA', null],
            [7, '76', 'INTERVENTORÍA', null],
            [7, '77', 'GASTOS DE OPERACIÓN, LOGÍSTICA Y EVALUACIÓN', null],

            // ---------------------------
            // 8. VIVIENDA, PENSIONES Y CESANTÍAS
            // ---------------------------
            [8, '00', 'ACCIONES REGULARES Y DEMÁS PROYECTOS', null],
            [8, '10', 'ACCIONES REGULARES Y DEMÁS PROYECTOS', null],

            // ---------------------------
            // 9. INFRAESTRUCTURA
            // ---------------------------
            [9, '24', 'CONSTRUCCIONES Y ADECUACIONES', null],
        ];

        foreach ($subunits as $su) {
            DependencySubunit::create([
                'dependency_unit_id' => $su[0],
                'subunit_code'       => $su[1],
                'name'               => $su[2],
                'description'        => $su[3],
            ]);
        }
    }
}
