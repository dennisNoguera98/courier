<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
       /* Schema::create('clientes', function (Blueprint $table) {
            $table->id('idClientes'); // PRIMARY KEY, AUTO_INCREMENT
            $table->unsignedBigInteger('Prioridades_idPrioridades');
            $table->unsignedBigInteger('Personas_idPersonas');
            $table->timestamps();

            // Claves foráneas
            $table->foreign('Prioridades_idPrioridades')->references('idPrioridades')->on('prioridades')->onDelete('cascade');
            $table->foreign('Personas_idPersonas')->references('idPersonas')->on('personas')->onDelete('cascade');
        }); */
    }

    public function down()
    {
        Schema::dropIfExists('clientes');
    }
};