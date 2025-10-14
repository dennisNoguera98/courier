<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ubicaciones', function (Blueprint $table) {
            // Paso 1: agregamos el campo barrio_id, inicialmente nullable
            $table->unsignedBigInteger('barrio_id')->nullable()->after('coordenadas');
            $table->index('barrio_id', 'ubicaciones_barrio_id_index');

            $table->foreign('barrio_id', 'ubicaciones_barrio_id_foreign')
                ->references('id')->on('barrios')
                ->onDelete('restrict'); // podés usar cascade o set null si preferís otro comportamiento
        });
    }

    public function down(): void
    {
        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->dropForeign('ubicaciones_barrio_id_foreign');
            $table->dropIndex('ubicaciones_barrio_id_index');
            $table->dropColumn('barrio_id');
        });
    }
};