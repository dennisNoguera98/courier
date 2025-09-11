<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExtractoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('extractos')->insert([
            [
                'gestor_id' => 1,
                'entrega_id' => 1,
                'cliente_id' => 1,
                'orden_ruta' => 1,
                'estado' => 1,
                'sync_status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gestor_id' => 1,
                'entrega_id' => 1,
                'cliente_id' => 2,
                'orden_ruta' => 2,
                'estado' => 1,
                'sync_status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
