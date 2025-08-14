<?php
// database/seeders/CentrosSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CentrosSeeder extends Seeder
{
    public function run(): void
    {
        $centros = [
            ['nom_centro' => 'Despacho Dirección', 'id_municipio' => 'LETICIA', 'barrio_centro' => '', 'direc_centro' => 'Calle 12 No. 10 – 60 Centro', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '85811', 'id_regional' => 'AMAZONAS', 'departamento' => ''],
            ['nom_centro' => 'Centro para la Biodiversidad y el Turismo del Amaz', 'id_municipio' => 'LETICIA', 'barrio_centro' => '', 'direc_centro' => 'Carretera Vía Leticia Tarapaca Km 1.3', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '85811 85816', 'id_regional' => 'AMAZONAS', 'departamento' => ''],
            ['nom_centro' => 'Despacho Dirección', 'id_municipio' => 'MEDELLÍN', 'barrio_centro' => '', 'direc_centro' => 'Calle 51 No. 57 – 70', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '42007 – 42327', 'id_regional' => 'ANTIOQUIA', 'departamento' => ''],
            ['nom_centro' => 'Centro de los Recursos Naturales Renovables –La Sa', 'id_municipio' => 'CALDAS', 'barrio_centro' => '', 'direc_centro' => 'Km 6 Vía a la Pintada Caldas Antioquia', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '42962 – 42935', 'id_regional' => 'ANTIOQUIA', 'departamento' => ''],
            ['nom_centro' => 'Centro del Diseño y Manufactura del Cuero', 'id_municipio' => 'ITAGÜÍ', 'barrio_centro' => '', 'direc_centro' => 'Cl.63 58B–03 Itagui–Calatrava', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '43211', 'id_regional' => 'ANTIOQUIA', 'departamento' => ''],
            ['nom_centro' => 'Centro de Formación en Diseño, Confección y Moda.', 'id_municipio' => 'ITAGÜÍ', 'barrio_centro' => '', 'direc_centro' => 'Cl.63 58B–03 Itagui–Calatrava', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '43209', 'id_regional' => 'ANTIOQUIA', 'departamento' => ''],
            ['nom_centro' => 'Centro para el Desarrollo del Hábitat y la Constru', 'id_municipio' => 'MEDELLÍN', 'barrio_centro' => '', 'direc_centro' => 'Diag.104 69–120 El pedregal', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '43305', 'id_regional' => 'ANTIOQUIA', 'departamento' => ''],
            ['nom_centro' => 'Centro de Tecnología de la Manufactura Avanzada.', 'id_municipio' => 'MEDELLIN', 'barrio_centro' => '', 'direc_centro' => 'Diag.104 69–120 El pedregal', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '43306', 'id_regional' => 'ANTIOQUIA', 'departamento' => ''],
            ['nom_centro' => 'Centro Tecnológico del Mobiliario', 'id_municipio' => 'ITAGÜÍ', 'barrio_centro' => '', 'direc_centro' => 'Cl.63 58B–03 Itagui–Calatrava', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '43104 – 43101', 'id_regional' => 'ANTIOQUIA', 'departamento' => ''],
            ['nom_centro' => 'Centro Textil y de Gestión Industrial', 'id_municipio' => 'MEDELLÍN', 'barrio_centro' => '', 'direc_centro' => 'Diag.104 69–120 El pedregal', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '43620', 'id_regional' => 'ANTIOQUIA', 'departamento' => ''],
            ['nom_centro' => 'Centro de Comercio', 'id_municipio' => 'MEDELLÍN', 'barrio_centro' => '', 'direc_centro' => 'Calle 51 No. 57 – 70 Torre Sur Piso 5', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '42021 – 42012', 'id_regional' => 'ANTIOQUIA', 'departamento' => ''],
            ['nom_centro' => 'Centro de Servicios de Salud', 'id_municipio' => 'MEDELLÍN', 'barrio_centro' => '', 'direc_centro' => 'Calle 51 No. 57 – 70 Torre Sur', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '', 'id_regional' => 'ANTIOQUIA', 'departamento' => ''],
            ['nom_centro' => 'Centro de Servicios y Gestión Empresarial', 'id_municipio' => 'MEDELLÍN', 'barrio_centro' => '', 'direc_centro' => 'Calle 51 No. 57 – 70 Torre Norte', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '42010', 'id_regional' => 'ANTIOQUIA', 'departamento' => ''],
            ['nom_centro' => 'Complejo Tecnológico para la Gestión Agroempresari', 'id_municipio' => 'CAUCASIA', 'barrio_centro' => '', 'direc_centro' => 'Transversal 16 calle 33 No. 102', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '', 'id_regional' => 'ANTIOQUIA', 'departamento' => ''],
            ['nom_centro' => 'Complejo Tecnológico Minero Agroempresarial', 'id_municipio' => 'PUERTO BERRÍO', 'barrio_centro' => '', 'direc_centro' => 'Calle 50 Vía La Malena', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '43751', 'id_regional' => 'ANTIOQUIA', 'departamento' => ''],
            ['nom_centro' => 'Centro de la Innovación, la Agroindustria y la Avi', 'id_municipio' => 'RIONEGRO', 'barrio_centro' => '', 'direc_centro' => 'Carrera 1 No. 28–71', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '44100', 'id_regional' => 'ANTIOQUIA', 'departamento' => ''],
            ['nom_centro' => 'Complejo Tecnológico Agroindustrial, Pecuario y Tu', 'id_municipio' => 'APARTADO', 'barrio_centro' => '', 'direc_centro' => 'Kilometro 1 Salida Turbo', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '44235 – 44200', 'id_regional' => 'ANTIOQUIA', 'departamento' => ''],
            ['nom_centro' => 'Complejo Tecnológico, Turístico y Agroindustrial d', 'id_municipio' => 'SANTAFÉ DE ANTIOQUIA', 'barrio_centro' => '', 'direc_centro' => 'Calle 11 Nro. 12–42', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '43060', 'id_regional' => 'ANTIOQUIA', 'departamento' => ''],
            ['nom_centro' => 'Centro de Formación Minero Ambiental', 'id_municipio' => 'EL BAGRE', 'barrio_centro' => '', 'direc_centro' => 'El Bagre – Municipio El Bagre en el sector conocido como los', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '45126', 'id_regional' => 'ANTIOQUIA', 'departamento' => ''],
            ['nom_centro' => 'Despacho Dirección', 'id_municipio' => 'ARAUCA', 'barrio_centro' => '', 'direc_centro' => 'Carrera 20 No.28–163', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '74230 – 74245', 'id_regional' => 'ARAUCA', 'departamento' => ''],
            ['nom_centro' => 'Centro de Gestión y Desarrollo Agroindustrial de A', 'id_municipio' => 'ARAUCA', 'barrio_centro' => '', 'direc_centro' => 'Carrera 20 No.28–163', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '74230 – 74245', 'id_regional' => 'ARAUCA', 'departamento' => ''],
            ['nom_centro' => 'Centro para el Desarrollo Agroecologico y Agroindustrial', 'id_municipio' => '142', 'barrio_centro' => '', 'direc_centro' => 'Calle 9 19–120', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '52002', 'id_regional' => 'ATLÁNTICO', 'departamento' => 'ATLANTICO'],
            ['nom_centro' => 'Centro Nacional Colombo Alemán', 'id_municipio' => '126', 'barrio_centro' => '', 'direc_centro' => 'Calle 30 3E–164', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '52200', 'id_regional' => 'ATLÁNTICO', 'departamento' => 'ATLANTICO'],
            ['nom_centro' => 'Centro Industrial y de Aviación', 'id_municipio' => '126', 'barrio_centro' => '', 'direc_centro' => 'Calle 30 3E–164', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '52202', 'id_regional' => 'ATLÁNTICO', 'departamento' => 'ATLANTICO'],
            ['nom_centro' => 'Centro de Comercio y Servicios', 'id_municipio' => '126', 'barrio_centro' => '', 'direc_centro' => 'Carrera 43 42–40 Piso 10', 'img_centro' => '', 'fecha_reg_centro' => null, 'extension' => '52000', 'id_regional' => 'ATLÁNTICO', 'departamento' => 'ATLANTICO']
        ];

        foreach ($centros as $centro) {
            DB::table('centros')->insert(array_merge($centro, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }
}