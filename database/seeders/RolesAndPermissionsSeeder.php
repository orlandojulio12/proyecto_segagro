<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | PERMISOS
        |--------------------------------------------------------------------------
        */

        $permisos = [

            // INVENTARIO
            'inventario.view',
            'inventario.create',
            'inventario.edit',
            'inventario.delete',

            // INFRAESTRUCTURA
            'infraestructura.view',
            'infraestructura.create',
            'infraestructura.edit',
            'infraestructura.delete',

            // PRESUPUESTO
            'presupuesto.view',
            'presupuesto.create',
            'presupuesto.edit',
            'presupuesto.delete',

            // CONTRATOS
            'contratos.view',
            'contratos.create',
            'contratos.edit',
            'contratos.delete',

            // PQR
            'pqr.view',
            'pqr.create',
            'pqr.edit',
            'pqr.delete',

            // TRASLADOS
            'traslados.view',
            'traslados.create',
            'traslados.edit',
            'traslados.delete',

            // SEMOVIENTE
            'semoviente.view',
            'semoviente.create',
            'semoviente.edit',
            'semoviente.delete',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        /*
        |--------------------------------------------------------------------------
        | ROLES
        |--------------------------------------------------------------------------
        */

        $superAdmin = Role::firstOrCreate(['name' => 'SuperAdministrador']);
        $admin = Role::firstOrCreate(['name' => 'Administrador']);
        $infra = Role::firstOrCreate(['name' => 'Gestor Infraestructura']);
        $inventario = Role::firstOrCreate(['name' => 'Gestor Inventario']);
        $presupuesto = Role::firstOrCreate(['name' => 'Gestor Presupuesto']);
        $contratos = Role::firstOrCreate(['name' => 'Gestor Contratacion']);
        $pqr = Role::firstOrCreate(['name' => 'Gestor PQR']);
        $traslados = Role::firstOrCreate(['name' => 'Gestor Traslado']);

        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN
        |--------------------------------------------------------------------------
        */

        $superAdmin->syncPermissions(Permission::all());

        /*
        |--------------------------------------------------------------------------
        | ADMIN (solo visualizar todo)
        |--------------------------------------------------------------------------
        */

        $admin->syncPermissions([
            'inventario.view',
            'infraestructura.view',
            'presupuesto.view',
            'contratos.view',
            'pqr.view',
            'traslados.view',
            'semoviente.view'
        ]);

        /*
        |--------------------------------------------------------------------------
        | GESTOR INFRAESTRUCTURA
        | Infraestructura + Inventario (menos semoviente) + Traslados
        |--------------------------------------------------------------------------
        */

        $infra->syncPermissions([

            // infraestructura
            'infraestructura.view',
            'infraestructura.create',
            'infraestructura.edit',
            'infraestructura.delete',

            // inventario
            'inventario.view',
            'inventario.create',
            'inventario.edit',
            'inventario.delete',

            // traslados
            'traslados.view',
            'traslados.create',
            'traslados.edit',
            'traslados.delete',
        ]);

        /*
        |--------------------------------------------------------------------------
        | GESTOR INVENTARIO
        | Inventario completo menos semoviente
        |--------------------------------------------------------------------------
        */

        $inventario->syncPermissions([
            'inventario.view',
            'inventario.create',
            'inventario.edit',
            'inventario.delete'
        ]);

        /*
        |--------------------------------------------------------------------------
        | GESTOR PRESUPUESTO
        |--------------------------------------------------------------------------
        */

        $presupuesto->syncPermissions([
            'presupuesto.view',
            'presupuesto.create',
            'presupuesto.edit',
            'presupuesto.delete'
        ]);

        /*
        |--------------------------------------------------------------------------
        | GESTOR CONTRATACION
        | Presupuesto: solo ver
        | Contratos: CRUD
        |--------------------------------------------------------------------------
        */

        $contratos->syncPermissions([

            'presupuesto.view',

            'contratos.view',
            'contratos.create',
            'contratos.edit',
            'contratos.delete'
        ]);

        /*
        |--------------------------------------------------------------------------
        | GESTOR PQR
        |--------------------------------------------------------------------------
        */

        $pqr->syncPermissions([
            'pqr.view',
            'pqr.create',
            'pqr.edit',
            'pqr.delete'
        ]);

        /*
        |--------------------------------------------------------------------------
        | GESTOR TRASLADOS
        |--------------------------------------------------------------------------
        */

        $traslados->syncPermissions([
            'traslados.view',
            'traslados.create',
            'traslados.edit',
            'traslados.delete'
        ]);
    }
}
