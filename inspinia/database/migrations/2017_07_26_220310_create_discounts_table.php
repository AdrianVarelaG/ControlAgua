<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->char('temporary',1);
            $table->char('movement_type',2);
            $table->char('type',1);            
            $table->date('intial_date');
            $table->date('final_date');
            $table->integer('age');
            $table->float('percent',8,2);
            $table->float('amount',11,2);
            $table->string('observation');
            $table->string('created_by');
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
        Schema::drop('discounts', function (Blueprint $table) {
            //
        });
    }
}
