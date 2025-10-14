<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('grupos_entrega_detalles', function (Blueprint $table) {
            $table->bigIncrements('detalle_id');
            $table->unsignedBigInteger('grupo_id');
            $table->unsignedBigInteger('extracto_id');
            $table->integer('orden')->nullable();
            $table->decimal('distancia_km', 8, 3)->nullable();
            $table->timestamps();

            // Relaciones
            $table->foreign('grupo_id')
                  ->references('grupo_id')->on('grupos_entrega')
                  ->onDelete('cascade');

            $table->foreign('extracto_id')
                  ->references('extracto_id')->on('extractos')
                  ->onDelete('cascade');

            // Índices
            $table->unique(['grupo_id', 'extracto_id']);
            $table->index(['grupo_id', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupos_entrega_detalles');
    }
};