<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/* class AddCedulaToPersonasTable extends Migration
{
    public function up()
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->string('cedula', 20)->unique()->nullable();
        });
    }

    public function down()
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->dropColumn('cedula');
        });
    }
} */