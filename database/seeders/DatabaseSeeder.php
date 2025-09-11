<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PersonaSeeder::class,
            UsuarioSeeder::class,
            PerfilSeeder::class,
            PerfilUsuarioSeeder::class,
            PrioridadSeeder::class,
            UbicacionSeeder::class,
            ClienteSeeder::class,
            EntregaSeeder::class,
            ExtractoSeeder::class,
        ]);
    }
}
