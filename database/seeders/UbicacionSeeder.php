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
                'coordenadas' => '-25.5056382,-57.571227',
                'descripcion' => 'MCAL LOPEZ C/ E. SCHOENFELD',
                'estado' => 'Activo',
                'fecha_hora' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coordenadas' => '-25.5056699,-57.5711247',
                'descripcion' => 'MCAL LOPEZ C/ E. SCHOENFELD',
                'estado' => 'Activo',
                'fecha_hora' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coordenadas' => '-25.5071409,-57.5708366',
                'descripcion' => 'CARLOS A. LOPEZ',
                'estado' => 'Activo',
                'fecha_hora' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coordenadas' => '-25.5062394,-57.5707631',
                'descripcion' => 'PROF. SCHOENFELD',
                'estado' => 'Activo',
                'fecha_hora' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coordenadas' => '-25.506195,-57.5707384',
                'descripcion' => 'PROF. SCHOENFELD',
                'estado' => 'Activo',
                'fecha_hora' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coordenadas' => '-25.5066337,-57.5720221',
                'descripcion' => 'CARLOS A. LOPEZ',
                'estado' => 'Activo',
                'fecha_hora' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coordenadas' => '-25.5063022,-57.5722194',
                'descripcion' => 'COLON',
                'estado' => 'Activo',
                'fecha_hora' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coordenadas' => '-25.5071405,-57.5707365',
                'descripcion' => 'CARLOS A. LOPEZ',
                'estado' => 'Activo',
                'fecha_hora' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coordenadas' => '-25.5072654,-57.5708466',
                'descripcion' => 'CARLOS A. LOPEZ',
                'estado' => 'Activo',
                'fecha_hora' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
