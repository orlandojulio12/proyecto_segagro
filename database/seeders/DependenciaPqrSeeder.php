<?php

namespace Database\Seeders;

use App\Models\Pqr\DependenciaPqr;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DependenciaPqrSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dependencias = ['Formación', 'GAA', 'GAE'];
        foreach ($dependencias as $dep) {
            DependenciaPqr::create(['name' => $dep]);
        }
    }
}
