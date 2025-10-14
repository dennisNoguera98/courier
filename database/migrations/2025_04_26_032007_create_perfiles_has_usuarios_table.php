<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerfilesHasUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perfiles_has_usuarios', function (Blueprint $table) {
            $table->unsignedBigInteger('Perfiles_idPerfiles');
            $table->integer('Usuarios_usuario_id');

            $table->primary(['Perfiles_idPerfiles', 'Usuarios_usuario_id'], 'pk_perfiles_usuarios');

            $table->foreign('Perfiles_idPerfiles')->references('idPerfiles')->on('perfiles');
            $table->foreign('Usuarios_usuario_id')->references('usuario_id')->on('usuarios');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perfiles_has_usuarios');
    }
}
