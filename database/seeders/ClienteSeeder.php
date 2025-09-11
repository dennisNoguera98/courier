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
            ]
        ]);
    }
}
