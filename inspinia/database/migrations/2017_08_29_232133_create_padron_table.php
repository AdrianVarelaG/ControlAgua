<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePadronTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('padron', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('contrato');
            $table->integer('cuenta')->nullable();
            $table->string('direccion');
            $table->string('calle');
            $table->string('nro_ext');
            $table->string('nro_int');
            $table->date('ultimo_mes')->nullable();
            $table->date('ultimo_recibo')->nullable();
            $table->integer('meses_adeudo')->nullable();
            $table->float('adeudo',11,2)->nullable();
            $table->string('barrio');
            $table->string('status');
            $table->string('nota');
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
        Schema::drop('padron');
    }
}
