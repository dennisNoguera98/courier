<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        /* Schema::create('prioridades', function (Blueprint $table) {
            $table->id('idPrioridades'); // idPrioridades, autoincremental
            $table->string('nombre_prioridad', 45);
            $table->timestamps();
        }); */
    }

    public function down()
    {
        Schema::dropIfExists('prioridades');
    }
};