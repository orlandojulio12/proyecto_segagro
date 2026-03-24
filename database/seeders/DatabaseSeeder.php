<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\CentrosSeeder;
use Database\Seeders\DependencisSeeder;
use Database\Seeders\HiringModalitiesSeeder;
use Database\Seeders\SedesSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\ContractTypesSeeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Llamar al seeder de sedes
        $this->call([
            CentrosSeeder::class,
            SedesSeeder::class,
            DependencisSeeder::class,
            HiringModalitiesSeeder::class,
            ContractTypesSeeder::class,
            CatalogProductSeeder::class,
            DependencyUnitsSeeder::class,
            DependencySubunitsSeeder::class,
            RolesAndPermissionsSeeder::class
        ]);

         $user = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456'),
        ]);

        $user->assignRole('SuperAdministrador');
    }
}
