<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id('idPersonas'); // ID de la tabla personas
            $table->string('nombre_persona', 45);
            $table->string('apellido_persona', 45);
            $table->string('coordenadas_persona', 45)->nullable(); // Puede ser opcional
            $table->string('direccion_persona', 200);
            $table->string('celular_principal_persona', 45);
            $table->string('celular_secundario_persona', 40)->nullable();
            $table->string('observacion', 200)->nullable();
            $table->timestamps();
        });
    }
    /* * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personas');
    }
}
