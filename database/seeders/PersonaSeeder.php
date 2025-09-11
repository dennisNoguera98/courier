<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('personas')->insert([
            [
                'nombre_persona' => 'Juan',
                'apellido_persona' => 'Pérez',
                'coordenadas_persona' => '-25.2637,-57.5759',
                'direccion_persona' => 'Av. Principal 123',
                'celular_principal_persona' => '0981123456',
                'celular_secundario_persona' => null,
                'observacion' => 'Cliente preferencial',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_persona' => 'María',
                'apellido_persona' => 'Gómez',
                'coordenadas_persona' => '-25.3000,-57.6000',
                'direccion_persona' => 'Calle Secundaria 456',
                'celular_principal_persona' => '0981987654',
                'celular_secundario_persona' => '0971222333',
                'observacion' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
