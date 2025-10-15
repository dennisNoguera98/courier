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
            ],
            [
                'gestor_id' => 1,
                'entrega_id' => 1,
                'cliente_id' => 3,
                'orden_ruta' => 3,
                'estado' => 1,
                'sync_status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gestor_id' => 1,
                'entrega_id' => 1,
                'cliente_id' => 4,
                'orden_ruta' => 4,
                'estado' => 1,
                'sync_status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gestor_id' => 1,
                'entrega_id' => 1,
                'cliente_id' => 5,
                'orden_ruta' => 5,
                'estado' => 1,
                'sync_status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gestor_id' => 1,
                'entrega_id' => 1,
                'cliente_id' => 6,
                'orden_ruta' => 6,
                'estado' => 1,
                'sync_status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gestor_id' => 1,
                'entrega_id' => 1,
                'cliente_id' => 7,
                'orden_ruta' => 7,
                'estado' => 1,
                'sync_status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gestor_id' => 1,
                'entrega_id' => 1,
                'cliente_id' => 8,
                'orden_ruta' => 8,
                'estado' => 1,
                'sync_status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gestor_id' => 1,
                'entrega_id' => 1,
                'cliente_id' => 9,
                'orden_ruta' => 9,
                'estado' => 1,
                'sync_status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
