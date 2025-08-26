<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('ubicaciones', function (Blueprint $table) {
            // Ajustá el largo si querés. Nullable para poder cargarlo de a poco.
            $table->string('barrio', 120)->nullable()->after('coordenadas');
            $table->index('barrio'); // para agrupar/filtrar rápido
        });
    }

    public function down(): void {
        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->dropIndex(['barrio']);
            $table->dropColumn('barrio');
        });
    }
};