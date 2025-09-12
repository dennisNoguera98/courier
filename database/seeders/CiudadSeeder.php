<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ciudad;

class CiudadSeeder extends Seeder
{
    public function run(): void
    {
        $ciudades = [
            ['nombre_ciudad' => 'Villeta', 'cobertura' => true, 'sync_status' => 'pending'],
            ['nombre_ciudad' => 'Guarambaré', 'cobertura' => false, 'sync_status' => 'pending'],
            ['nombre_ciudad' => 'Luque', 'cobertura' => false, 'sync_status' => 'pending'],
            ['nombre_ciudad' => 'Capiatá', 'cobertura' => false, 'sync_status' => 'pending'],
            ['nombre_ciudad' => 'J. A. Saldivar', 'cobertura' => false, 'sync_status' => 'pending'],
            ['nombre_ciudad' => 'Ypané', 'cobertura' => false, 'sync_status' => 'pending'],
            ['nombre_ciudad' => 'Fernando de la Mora', 'cobertura' => false, 'sync_status' => 'pending'],
        ];

        foreach ($ciudades as $ciudad) {
            Ciudad::create($ciudad);
        }
    }
}
