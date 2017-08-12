<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('citizen_id')->unsigned();
            $table->foreign('citizen_id')->references('id')->on('citizens');
            $table->integer('rate_id')->unsigned();
            $table->foreign('rate_id')->references('id')->on('rates');
            $table->integer('administration_id')->unsigned();
            $table->foreign('administration_id')->references('id')->on('administrations');
            $table->string('number');
            $table->date('date');
            $table->integer('state_id')->unsigned();
            $table->foreign('state_id')->references('id')->on('states');
            $table->integer('municipality_id')->unsigned();
            $table->foreign('municipality_id')->references('id')->on('municipalities');
            $table->string('street',100);
            $table->string('number_ext',20);
            $table->string('number_int',20);
            $table->string('neighborhood',100);            
            $table->string('postal_code',20);
            $table->char('status',1);                        
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
        Schema::drop('contracts');
    }
}
