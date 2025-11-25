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

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('123456'), // 👈 contraseña personalizada
        ]);

        // Llamar al seeder de sedes
        $this->call([
            CentrosSeeder::class,
            SedesSeeder::class,
            DependencisSeeder::class,
            HiringModalitiesSeeder::class,
            ContractTypesSeeder::class,
            CatalogProductSeeder::class
        ]);
    }
}
