<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('clientes')->insert([
            [
                'Prioridades_idPrioridades' => 1,
                'Personas_idPersonas' => 1,
                'Ubicaciones_idUbicaciones' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Prioridades_idPrioridades' => 2,
                'Personas_idPersonas' => 2,
                'Ubicaciones_idUbicaciones' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Prioridades_idPrioridades' => 2,
                'Personas_idPersonas' => 3,
                'Ubicaciones_idUbicaciones' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Prioridades_idPrioridades' => 2,
                'Personas_idPersonas' => 4,
                'Ubicaciones_idUbicaciones' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Prioridades_idPrioridades' => 2,
                'Personas_idPersonas' => 5,
                'Ubicaciones_idUbicaciones' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Prioridades_idPrioridades' => 2,
                'Personas_idPersonas' => 6,
                'Ubicaciones_idUbicaciones' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Prioridades_idPrioridades' => 2,
                'Personas_idPersonas' => 7,
                'Ubicaciones_idUbicaciones' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Prioridades_idPrioridades' => 2,
                'Personas_idPersonas' => 8,
                'Ubicaciones_idUbicaciones' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Prioridades_idPrioridades' => 2,
                'Personas_idPersonas' => 9,
                'Ubicaciones_idUbicaciones' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
