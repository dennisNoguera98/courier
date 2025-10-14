<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->integer('usuario_id')->primary();
            $table->string('Usuarios_usuario', 45);
            $table->string('Usuarios_contrasena', 200);
            $table->unsignedBigInteger('Personas_idPersonas');

            $table->foreign('Personas_idPersonas')->references('idPersonas')->on('personas');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}
