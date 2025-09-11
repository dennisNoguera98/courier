<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerfilSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('perfiles')->insert([
            ['nombre_perfil' => 'gestor', 'descripcion_perfil' => 'Gestor de entregas', 'created_at' => now(), 'updated_at' => now()],
            ['nombre_perfil' => 'courier', 'descripcion_perfil' => 'Repartidor de extractos', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
