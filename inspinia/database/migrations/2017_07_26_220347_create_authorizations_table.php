<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authorizations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('position',100);
            $table->string('email',50);
            $table->char('status',1);
            $table->timestamps();
            if(env('DB_CONNECTION') == 'pgsql'){
                $table->text('avatar')->nullable();
            }                            
        });
        
        if(env('DB_CONNECTION') == 'mysql'){
            DB::statement("ALTER TABLE authorizations ADD avatar MEDIUMBLOB");
        }    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('authorizations', function (Blueprint $table) {
            //
        });
    }
}
