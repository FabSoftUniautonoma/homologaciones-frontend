<?php

namespace Database\Seeders;

use App\Models\Pais;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaisesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pais::create([
            'nombre' => 'Colombia',
        ]);
        Pais::create([
            'nombre' => 'Peru',
        ]);
        Pais::create([
            'nombre' => 'Venezuela',
        ]);
    }
}
