<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('grupos_entrega', function (Blueprint $table) {
            $table->bigIncrements('grupo_id');

            // entregas.entrega_id es BIGINT UNSIGNED -> ok usar unsignedBigInteger
            $table->unsignedBigInteger('entrega_id');

            // usuarios.usuario_id es INT(11) (firmado) -> usar integer(), nullable por SET NULL
            $table->integer('id_courier')->nullable();

            $table->string('estado', 20)->default('pendiente');
            $table->timestamps();

            // FKs
            $table->foreign('entrega_id')
                  ->references('entrega_id')->on('entregas')
                  ->onDelete('cascade');

            $table->foreign('id_courier')
                  ->references('usuario_id')->on('usuarios')
                  ->nullOnDelete(); // ON DELETE SET NULL

            // Índices
            $table->index('entrega_id');
            $table->index('id_courier');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupos_entrega');
    }
};
