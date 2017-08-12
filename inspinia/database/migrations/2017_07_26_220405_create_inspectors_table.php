<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInspectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspectors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('ID_number',50);
            $table->string('phone',25);
            $table->string('mobile',25);
            $table->string('email',50);
            $table->char('status',1);
            $table->timestamps();
            if(env('DB_CONNECTION') == 'pgsql'){
                $table->text('avatar')->nullable();
            }                            
        });
        
        if(env('DB_CONNECTION') == 'mysql'){
            DB::statement("ALTER TABLE inspectors ADD avatar MEDIUMBLOB");
        }    
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('inspectors', function (Blueprint $table) {
            //
        });
    }
}
