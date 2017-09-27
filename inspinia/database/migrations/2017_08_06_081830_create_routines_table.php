<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routines', function (Blueprint $table) {
            $table->increments('id');
            $table->char('rate_type', 1);
            $table->char('month', 2);
            $table->char('year', 4);            
            $table->char('month_consume', 2);
            $table->char('year_consume', 4);
            $table->bigInteger('start');
            $table->bigInteger('end');
            $table->string('created_by');
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
        Schema::drop('routines');
    }
}
