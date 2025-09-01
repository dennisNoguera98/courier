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
        Schema::create('Perfiles_has_Usuarios', function (Blueprint $table) {
            $table->unsignedBigInteger('Perfiles_idPerfiles');
            $table->integer('Usuarios_idtable1');

            $table->primary(['Perfiles_idPerfiles', 'Usuarios_idtable1'], 'pk_perfiles_usuarios');

            $table->foreign('Perfiles_idPerfiles')->references('idPerfiles')->on('perfiles');
            $table->foreign('Usuarios_idtable1')->references('idtable1')->on('Usuarios');
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
