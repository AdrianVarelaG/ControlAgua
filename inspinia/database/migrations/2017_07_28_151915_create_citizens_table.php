<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitizensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citizens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('state_id')->unsigned();
            $table->foreign('state_id')->references('id')->on('states');
            $table->integer('municipality_id')->unsigned();
            $table->foreign('municipality_id')->references('id')->on('municipalities');
            $table->date('birthdate');
            $table->string('street',100);
            $table->string('number_ext',20);
            $table->string('number_int',20);
            $table->string('neighborhood',100);            
            $table->string('postal_code',20);            
            $table->string('ID_number', 50);
            $table->string('RFC', 50);            
            $table->string('name',100);
            $table->string('profession',100);
            $table->string('email',50);
            $table->string('phone',25);
            $table->string('mobile',25);
            $table->char('status',1);            
            $table->timestamps();
            if(env('DB_CONNECTION') == 'pgsql'){
                $table->text('avatar')->nullable();
            }                    
        });

        if(env('DB_CONNECTION') == 'mysql'){
            DB::statement("ALTER TABLE citizens ADD avatar MEDIUMBLOB");
        }    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('citizens');
    }
}
