<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntregasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entregas', function (Blueprint $table) {
            $table->integer('idEntregas')->primary();
            $table->dateTime('fecha_entrega');
            $table->unsignedBigInteger('Clientes_idClientes');
            $table->string('estado', 45);

            $table->foreign('Clientes_idClientes')->references('idClientes')->on('clientes');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entregas');
    }
}
