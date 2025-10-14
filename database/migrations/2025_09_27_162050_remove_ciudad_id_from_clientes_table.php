<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Primero borramos la FK e índice
            if (Schema::hasColumn('clientes', 'ciudad_id')) {
                $table->dropForeign('clientes_ciudad_id_foreign');
                $table->dropIndex('clientes_ciudad_id_index');
                $table->dropColumn('ciudad_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->unsignedBigInteger('ciudad_id')->nullable()->after('Ubicaciones_idUbicaciones');
            $table->index('ciudad_id', 'clientes_ciudad_id_index');
            $table->foreign('ciudad_id', 'clientes_ciudad_id_foreign')
                  ->references('id')->on('ciudades')
                  ->onDelete('cascade');
        });
    }
};