<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUbicacionesTable extends Migration
{
    public function up()
    {
        Schema::create('ubicaciones', function (Blueprint $table) {
            $table->id('idUbicaciones');
            $table->string('coordenadas', 45);
            $table->string('descripcion', 200);
            $table->string('estado', 45);
            $table->dateTime('fecha_hora');

            
            $table->timestamps();
        }); 
    }

    public function down()
    {
        Schema::dropIfExists('ubicaciones');
    }
}
