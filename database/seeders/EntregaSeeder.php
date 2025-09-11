<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntregaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('entregas')->insert([
            [
                'nombre_entrega' => 'Entrega Julio',
                'estado' => 1,
                'observaciones' => 'Entrega mensual',
                'gestor_id' => 1,
                'sync_status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
