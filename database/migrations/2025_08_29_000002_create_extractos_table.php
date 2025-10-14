<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtractosTable extends Migration {
    public function up(): void {
        Schema::create('extractos', function (Blueprint $table) {
            $table->id('extracto_id');
            $table->integer('gestor_id');
            $table->unsignedBigInteger('entrega_id');

            // relación con entrega (cascade delete)
            $table->foreign('entrega_id')
                  ->references('entrega_id')
                  ->on('entregas')
                  ->onDelete('cascade');

            // relación con cliente (si se borra cliente, quedan extractos huérfanos)
            $table->unsignedBigInteger('cliente_id');

            // orden en la ruta
            $table->integer('orden_ruta')->nullable();

            // estado del extracto
            $table->unsignedBigInteger('estado');

            $table->string('sync_status');

            // gestor que creó el extracto
            $table->foreign('gestor_id')
                  ->references('usuario_id')
                  ->on('usuarios')
                  ->onDelete('cascade');

            $table->timestamps();

            // index para optimizar búsquedas por entrega
            $table->index('entrega_id');
        });
    }

    public function down(): void {
        Schema::dropIfExists('extractos');
    }
};
