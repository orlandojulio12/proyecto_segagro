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

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

       /*  User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('123456'), // 👈 contraseña personalizada
        ]);

         // Llamar al seeder de centros
        $this->call([
            CentrosSeeder::class,
        ]);
        // Llamar al seeder de sedes
        $this->call([
            SedesSeeder::class,
        ]); */
        // Llamar al seeder de modalidades de contrato
         $this->call(HiringModalitiesSeeder::class);

         // Llamar al seeder de Dependencias
         $this->call(DependencisSeeder::class);

          // Llamar al seeder de tipos de contratos
         $this->call(ContractTypesSeeder::class);
    }
}
