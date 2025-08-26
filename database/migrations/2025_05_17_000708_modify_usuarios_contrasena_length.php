<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUsuariosContrasenaLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    Schema::table('usuarios', function (Blueprint $table) {
        $table->string('Usuarios_contrasena', 100)->change();
    });
}

public function down()
{
    Schema::table('usuarios', function (Blueprint $table) {
        $table->string('Usuarios_contrasena', 45)->change();
    });
}
}
