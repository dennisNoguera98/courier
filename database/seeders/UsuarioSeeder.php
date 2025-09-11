<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('Usuarios')->insert([
            [
                'usuario_id' => 1,
                'Usuarios_usuario' => 'gestor1',
                'Usuarios_contrasena' => bcrypt('password123'),
                'Personas_idPersonas' => 1,
            ],
            [
                'usuario_id' => 2,
                'Usuarios_usuario' => 'courier1',
                'Usuarios_contrasena' => bcrypt('password456'),
                'Personas_idPersonas' => 2,
            ],
        ]);
    }
}
