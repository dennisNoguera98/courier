<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('personas')->insert([
            [
                'nombre_persona' => 'MIRANDA BENITEZ, GLADYS RAMONA',
                'apellido_persona' => '',
                'coordenadas_persona' => '-25.5056382,-57.571227',
                'direccion_persona' => 'MCAL LOPEZ C/ E. SCHOENFELD',
                'celular_principal_persona' => '0981123456',
                'celular_secundario_persona' => null,
                'observacion' => 'Cliente preferencial',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_persona' => 'MARTINEZ MIRANDA, MARCOS MATIAS',
                'apellido_persona' => '',
                'coordenadas_persona' => '-25.5056699,-57.5711247',
                'direccion_persona' => 'MCAL LOPEZ C/ E. SCHOENFELD',
                'celular_principal_persona' => '0981987654',
                'celular_secundario_persona' => '0971222333',
                'observacion' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_persona' => 'GIMENEZ DE PEREIRA, MIRYAN CAROLINA',
                'apellido_persona' => '',
                'coordenadas_persona' => '-25.5071409,-57.5708366',
                'direccion_persona' => 'CARLOS A. LOPEZ',
                'celular_principal_persona' => '0981987654',
                'celular_secundario_persona' => '0971222333',
                'observacion' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_persona' => 'BENITEZ, ROSANA',
                'apellido_persona' => '',
                'coordenadas_persona' => '-25.5062394,-57.5707631',
                'direccion_persona' => 'PROF. SCHOENFELD',
                'celular_principal_persona' => '0981987654',
                'celular_secundario_persona' => '0971222333',
                'observacion' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_persona' => 'IRRAZABAL FRETES, MARIA FATIMA',
                'apellido_persona' => '',
                'coordenadas_persona' => '-25.506195,-57.5707384',
                'direccion_persona' => 'PROF. SCHOENFELD',
                'celular_principal_persona' => '0981987654',
                'celular_secundario_persona' => '0971222333',
                'observacion' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_persona' => 'URBIETA RAMIREZ, HIGINIO',
                'apellido_persona' => '',
                'coordenadas_persona' => '-25.5066337,-57.5720221',
                'direccion_persona' => 'CARLOS A. LOPEZ',
                'celular_principal_persona' => '0981987654',
                'celular_secundario_persona' => '0971222333',
                'observacion' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_persona' => 'DIAZ DE VILLALBA, GLADYS TERESA',
                'apellido_persona' => '',
                'coordenadas_persona' => '-25.5063022,-57.5722194',
                'direccion_persona' => 'COLON',
                'celular_principal_persona' => '0981987654',
                'celular_secundario_persona' => '0971222333',
                'observacion' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_persona' => 'PEREIRA GIMENEZ, BLAS ALBERTO',
                'apellido_persona' => '',
                'coordenadas_persona' => '-25.5071405,-57.5707365',
                'direccion_persona' => 'CARLOS A. LOPEZ',
                'celular_principal_persona' => '0981987654',
                'celular_secundario_persona' => '0971222333',
                'observacion' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_persona' => 'PEREIRA, OSCAR MILCIADES',
                'apellido_persona' => '',
                'coordenadas_persona' => '-25.5072654,-57.5708466',
                'direccion_persona' => 'CARLOS A. LOPEZ',
                'celular_principal_persona' => '0981987654',
                'celular_secundario_persona' => '0971222333',
                'observacion' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_persona' => 'BARRETO, JUAN',
                'apellido_persona' => '',
                'coordenadas_persona' => '-25.5094754,-57.5570942',
                'direccion_persona' => 'RUTA A GUARAMBARE',
                'celular_principal_persona' => '0981987654',
                'celular_secundario_persona' => '0971222333',
                'observacion' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_persona' => 'NOGUERA, DENNIS',
                'apellido_persona' => '',
                'coordenadas_persona' => '-25.5089215,-57.5568786',
                'direccion_persona' => 'RUTA A GUARAMBARE',
                'celular_principal_persona' => '0981987654',
                'celular_secundario_persona' => '0971222333',
                'observacion' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
