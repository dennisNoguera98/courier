<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrioridadSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('prioridades')->insert([
            ['nombre_prioridad' => 'Alta', 'created_at' => now(), 'updated_at' => now()],
            ['nombre_prioridad' => 'Media', 'created_at' => now(), 'updated_at' => now()],
            ['nombre_prioridad' => 'Baja', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
