<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ciudad;

class CiudadSeeder extends Seeder
{
    public function run(): void
    {
        $ciudades = [
            ['nombre_ciudad' => 'Villeta', 'cobertura' => true],
            ['nombre_ciudad' => 'Guarambaré', 'cobertura' => false],
            ['nombre_ciudad' => 'Luque', 'cobertura' => false],
            ['nombre_ciudad' => 'Capiatá', 'cobertura' => false],
            ['nombre_ciudad' => 'J. A. Saldivar', 'cobertura' => false],
            ['nombre_ciudad' => 'Ypané', 'cobertura' => false],
            ['nombre_ciudad' => 'Fernando de la Mora', 'cobertura' => false],
        ];

        foreach ($ciudades as $ciudad) {
            Ciudad::create($ciudad);
        }
    }
}
