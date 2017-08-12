<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contract_id')->unsigned();
            $table->foreign('contract_id')->references('id')->on('contracts');
            $table->integer('citizen_id')->unsigned();
            $table->foreign('citizen_id')->references('id')->on('citizens');
            $table->integer('reading_id')->unsigned()->nullable();
            $table->foreign('reading_id')->references('id')->on('readings');
            $table->integer('payment_id')->unsigned()->nullable();
            $table->foreign('payment_id')->references('id')->on('payments');            
            $table->float('rate',11,2);
            $table->string('rate_description', 100);
            $table->date('date');
            $table->char('month',2);
            $table->char('year',4);            
            $table->char('month_consume',2);
            $table->char('year_consume',4);
            $table->float('total',11,2);
            $table->string('message', 400);
            $table->char('status', 1);
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
        Schema::drop('invoices');
    }
}
