<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UbicacionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ubicaciones')->insert([
            [
                'coordenadas' => '-25.2637,-57.5759',
                'descripcion' => 'Zona centro Asunción',
                'estado' => 'Activo',
                'fecha_hora' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coordenadas' => '-25.3000,-57.6000',
                'descripcion' => 'Zona norte Luque',
                'estado' => 'Activo',
                'fecha_hora' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coordenadas' => '-25.2800,-57.6500',
                'descripcion' => 'Zona San Lorenzo',
                'estado' => 'Inactivo',
                'fecha_hora' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
