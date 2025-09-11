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
            $table->id('entrega_id');
            $table->string('nombre_entrega');
            $table->unsignedBigInteger('estado');
            $table->text('observaciones')->nullable();
            $table->integer('gestor_id');
            $table->string('sync_status');

            // gestor que creó la entrega
            $table->foreign('gestor_id')
                  ->references('usuario_id')
                  ->on('Usuarios')
                  ->onDelete('cascade');

            $table->timestamps();
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
