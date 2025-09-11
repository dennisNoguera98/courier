<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerfilUsuarioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('Perfiles_has_Usuarios')->insert([
            ['Perfiles_idPerfiles' => 1, 'Usuarios_usuario_id' => 1], // Gestor
            ['Perfiles_idPerfiles' => 2, 'Usuarios_usuario_id' => 2], // Courier
        ]);
    }
}
