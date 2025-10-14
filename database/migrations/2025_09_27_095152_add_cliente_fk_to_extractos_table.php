<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('extractos', function (Blueprint $table) {
            // Asegurar tipo correcto (ya es unsignedBigInteger según tu dump; si no, descomenta):
            // $table->unsignedBigInteger('cliente_id')->change();

            // Índice si no existe
            $table->index('cliente_id', 'extractos_cliente_id_index');

            // Agregar FK (nombre explícito para poder dropear en down)
            $table->foreign('cliente_id', 'extractos_cliente_id_foreign')
                  ->references('idClientes')->on('clientes')
                  ->onDelete('cascade'); // o RESTRICT/SET NULL si preferís
        });
    }

    public function down(): void
    {
        Schema::table('extractos', function (Blueprint $table) {
            // Quitar FK e índice
            $table->dropForeign('extractos_cliente_id_foreign');
            $table->dropIndex('extractos_cliente_id_index');
        });
    }
};