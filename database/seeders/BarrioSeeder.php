<?php

namespace Database\Seeders;

use App\Models\Barrio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarrioSeeder extends Seeder
{
    public function run(): void
    {
        $barrios = [
            ['nombre_barrio' => 'San Jose', 'cobertura' => true, 'ciudad_id' => 1, 'sync_status' => 'pending'],
            ['nombre_barrio' => 'San Juan', 'cobertura' => true, 'ciudad_id' => 1, 'sync_status' => 'pending'],
            ['nombre_barrio' => 'San Jorge', 'cobertura' => true, 'ciudad_id' => 1, 'sync_status' => 'pending'],
            ['nombre_barrio' => 'San Roque', 'cobertura' => true, 'ciudad_id' => 1, 'sync_status' => 'pending'],
            ['nombre_barrio' => 'San Isidro', 'cobertura' => true, 'ciudad_id' => 1, 'sync_status' => 'pending'],
            ['nombre_barrio' => 'San Martin', 'cobertura' => true, 'ciudad_id' => 1, 'sync_status' => 'pending'],
            ['nombre_barrio' => 'Inmaculada', 'cobertura' => true, 'ciudad_id' => 1, 'sync_status' => 'pending'],
            ['nombre_barrio' => 'Sagrado Corazon de Jesus', 'cobertura' => true, 'ciudad_id' => 1],
            ['nombre_barrio' => 'Sol Naciente', 'cobertura' => true, 'ciudad_id' => 1],
            ['nombre_barrio' => "Surubiy", 'cobertura' => false, 'ciudad_id' => 1],
        ];

        foreach ($barrios as $barrio) {
            Barrio::create($barrio);
        }
    }
}
