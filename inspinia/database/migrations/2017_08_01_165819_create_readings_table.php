<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('readings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contract_id')->unsigned();
            $table->foreign('contract_id')->references('id')->on('contracts');
            $table->integer('inspector_id')->unsigned();
            $table->foreign('inspector_id')->references('id')->on('inspectors');
            $table->char('month',2);
            $table->char('year',4);
            $table->date('date');
            $table->float('previous_reading',11,2);
            $table->float('current_reading',11,2);
            $table->string('observation',400)->nullable();            
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
        Schema::drop('readings');
    }
}
